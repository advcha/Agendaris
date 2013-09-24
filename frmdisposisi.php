<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

	//$row = array() ;
    $idsurat = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:"";
	$hal = "";
	$sessid = isset($_REQUEST['sessid'])?$_REQUEST['sessid']:"";
    if(isset($_REQUEST['FormName']) && $_REQUEST['FormName']=="disposisi")
  	{
		if($act != "del")
		{
		if($idsurat!='')	//edit
  		{
			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");

			for($i=1;$i<7;$i++)
			{
				$var_d = "disposisi".$i;
				$var_t = "tujuan_disposisi".$i;
				$var_g = "tgl_disposisi".$i;
				if($_POST[$var_d] == "" && $_POST[$var_t] == "" && $_POST[$var_g] == "")
				{
					$sqldisposisi = "DELETE FROM disposisi WHERE idsurat = ".$idsurat." AND disposisi_ke = ".$i;
					//echo "delete".$i.":".$sqldisposisi."<br>";
					if (!mysql_query($sqldisposisi, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
				else
				{
					//check disposisi
					$sqldisposisi = "SELECT COUNT(iddisposisi) AS count FROM disposisi WHERE idsurat = ".$idsurat." AND disposisi_ke = ".$i;
					$result = mysql_query($sqldisposisi) or die("Could not execute query.".mysql_error());
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					$count = $row['count'];
					if($count > 0)
					{
						$sqldisposisi = "UPDATE disposisi SET tgl_disposisi = '".
						$myfunction->dateformatSlashDb($_POST[$var_g])."',tujuan_disposisi = '".
						$_POST[$var_t]."',disposisi = '".$_POST[$var_d]."' WHERE idsurat = ".$idsurat." AND disposisi_ke = ".$i;
						//echo "update".$i.":".$sqldisposisi."<br>";
					}
					else
					{
						$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",".
						$i.",'".$myfunction->dateformatSlashDb($_POST[$var_g])."','".
						$_POST[$var_t]."','".$_POST[$var_d]."')";
						//echo "insert".$i.":".$sqldisposisi."<br>";
					}
					if (!mysql_query($sqldisposisi, $db)) 
					{
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
			}
			
  			$query = "UPDATE suratmasuk SET tgl_terima='".$myfunction->dateformatSlashDb($_POST['tgl_terima'])."',tgl_selesai='".$myfunction->dateformatSlashDb($_POST['tgl_selesai'])."',no_disposisi='".$_POST['no_disposisi']."',pengirim='".$_POST['pengirim']."',perihal='".$_POST['perihal']."',idkode=".$_POST['selkode'].",berkas='".$_POST['berkas']."',tgl_surat='".$myfunction->dateformatSlashDb($_POST['tgl_surat'])."',no_surat='".$_POST['no_surat']."',tujuan_akhir='".$_POST['tujuan_akhir']."',tgl_akhir='".$myfunction->dateformatSlashDb($_POST['tgl_akhir'])."',jam_sampai='".$_POST['jam_sampai']."',tgl_sampai='".$myfunction->dateformatSlashDb($_POST['tgl_sampai'])."',nama_penerima='".$_POST['nama_penerima']."' WHERE idsurat=".$idsurat;
			//echo "query:".$query."<br>";
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
  		}
  		else //add
		{
  			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
	
  			$query = "INSERT INTO suratmasuk(tgl_terima,tgl_selesai,no_disposisi,pengirim,perihal,idkode,berkas,tgl_surat,no_surat,tujuan_akhir,tgl_akhir,jam_sampai,tgl_sampai,nama_penerima) VALUES('".$myfunction->dateformatSlashDb($_POST['tgl_terima'])."','".$myfunction->dateformatSlashDb($_POST['tgl_selesai'])."',".$_POST['no_disposisi'].",'".$_POST['pengirim']."','".$_POST['perihal']."',".$_POST['selkode'].",'".$_POST['berkas']."','".$myfunction->dateformatSlashDb($_POST['tgl_surat'])."','".$_POST['no_surat']."','".$_POST['tujuan_akhir']."','".$myfunction->dateformatSlashDb($_POST['tgl_akhir'])."','".$_POST['jam_sampai']."','".$myfunction->dateformatSlashDb($_POST['tgl_sampai'])."','".$_POST['nama_penerima']."')";
			//echo "query:".$query."<br>";
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
			$idsurat = mysql_insert_id();

			if(!($_POST['tujuan_disposisi1'] == "" && $_POST['tgl_disposisi1'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",1,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi1'])."','".$_POST['tujuan_disposisi1']."','".$_POST['disposisi1']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if(!($_POST['tujuan_disposisi2'] == "" && $_POST['tgl_disposisi2'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",2,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi2'])."','".$_POST['tujuan_disposisi2']."','".$_POST['disposisi2']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if(!($_POST['tujuan_disposisi3'] == "" && $_POST['tgl_disposisi3'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",3,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi3'])."','".$_POST['tujuan_disposisi3']."','".$_POST['disposisi3']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if(!($_POST['tujuan_disposisi4'] == "" && $_POST['tgl_disposisi4'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",4,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi4'])."','".$_POST['tujuan_disposisi4']."','".$_POST['disposisi4']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if(!($_POST['tujuan_disposisi5'] == "" && $_POST['tgl_disposisi5'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",5,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi5'])."','".$_POST['tujuan_disposisi5']."','".$_POST['disposisi5']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if(!($_POST['tujuan_disposisi6'] == "" && $_POST['tgl_disposisi6'] == ""))
			{
				$sqldisposisi = "INSERT INTO disposisi (idsurat,disposisi_ke,tgl_disposisi,tujuan_disposisi,disposisi) VALUES (".$idsurat.",6,'".$myfunction->dateformatSlashDb($_POST['tgl_disposisi6'])."','".$_POST['tujuan_disposisi6']."','".$_POST['disposisi6']."')";
				if (!mysql_query($sqldisposisi, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			
  		}
		}
		else	//delete
		{
			$SQL = "DELETE FROM suratmasuk WHERE idsurat = ".$idsurat;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}

			$SQL = "DELETE FROM disposisi WHERE idsurat = ".$idsurat;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
  	}
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
		<script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date.js" type="text/javascript"></script>
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<script src="js/jquery.datePicker.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.date-pick').datePicker({autoFocusNextInput: true,startDate:'01/01/2000'});
				jQuery("#no_disposisi").focus();
				jQuery('#no_disposisi').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#jam_sampai").focus();
					}
				});
				jQuery('#jam_sampai').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_sampai").focus();
					}
				});
				jQuery('#tgl_sampai').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#selkode").focus();
					}
				});
				jQuery('#selkode').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#berkas").focus();
					}
					if(e.keyCode==38){	//up
						getKodeSurat(jQuery('#selkode').val());
					}
					if(e.keyCode==40){	//down
						getKodeSurat(jQuery('#selkode').val());
					}
				});
				jQuery('#berkas').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_surat").focus();
					}
				});
				jQuery('#tgl_surat').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat").focus();
					}
				});
				jQuery('#no_surat').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#pengirim").focus();
					}
				});
				jQuery('#pengirim').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#perihal").focus();
					}
				});
				jQuery('#perihal').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_terima").focus();
					}
				});
				jQuery('#tgl_terima').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_selesai").focus();
					}
				});
				jQuery('#tgl_selesai').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#disposisi1").focus();
					}
				});
				jQuery('#disposisi1').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_disposisi1").focus();
					}
				});
				jQuery('#tujuan_disposisi1').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_disposisi1").focus();
					}
				});
				jQuery('#tgl_disposisi1').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#disposisi2").focus();
					}
				});
				jQuery('#disposisi2').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_disposisi2").focus();
					}
				});
				jQuery('#tujuan_disposisi2').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_disposisi2").focus();
					}
				});
				jQuery('#tgl_disposisi2').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#disposisi3").focus();
					}
				});
				jQuery('#disposisi3').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_disposisi3").focus();
					}
				});
				jQuery('#tujuan_disposisi3').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_disposisi3").focus();
					}
				});
				jQuery('#tgl_disposisi3').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#disposisi4").focus();
					}
				});
				jQuery('#disposisi4').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_disposisi4").focus();
					}
				});
				jQuery('#tujuan_disposisi4').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_disposisi4").focus();
					}
				});
				jQuery('#tgl_disposisi4').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#disposisi5").focus();
					}
				});
				jQuery('#disposisi5').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_disposisi5").focus();
					}
				});
				jQuery('#tujuan_disposisi5').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_disposisi5").focus();
					}
				});
				jQuery('#tgl_disposisi5').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tujuan_akhir").focus();
					}
				});
				jQuery('#tujuan_akhir').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_akhir").focus();
					}
				});
				jQuery('#tgl_akhir').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery(":button:last").focus();
					}
				});
				
				<?php
					if($idsurat != "")
					{
						$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
						mysql_select_db($database) or die("Error conecting to db.");
						
						$SQL = "SELECT s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.idkode,
							s.berkas,s.tgl_surat,s.no_surat,s.jam_sampai,s.tgl_sampai,s.nama_penerima,
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
							LEFT JOIN disposisi d ON s.idsurat=d.idsurat WHERE s.idsurat = ".$idsurat." 
							GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal";
						//echo "sql:".$SQL."<br>";
						$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
					}
					else
					{
						$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
						mysql_select_db($database) or die("Error conecting to db.");
						
						$SQL = "SELECT MAX(no_disposisi) AS max_no FROM suratmasuk 
						WHERE SUBSTR(tgl_terima,1,4) = '".date('Y')."'";
						$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
						$max_no = $row['max_no'];
						$next_no = $max_no + 1;
				?>
						jQuery("#no_disposisi").val("<?php echo $next_no; ?>");
				<?php
					}
				?>
			});
			
			jQuery.fn.addItems = function(data) {
				return this.each(function() {
					var list = this;
					$.each(data, function(index, itemData) {
						var option = new Option(itemData.Text, itemData.Value);
						list.add(option);
					});
				});
			};
			
			function getKodeSurat(idkode,sessid)
			{
				//if(idkode == '0')
				//	idkode = jQuery('#selkode').val();
				var kode = jQuery.ajax({
					url: 'kode.php?search=false&idkode='+idkode+'&sessid='+sessid, 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Kode Surat Belum Ada.');
					}
				}).responseText;
				
				var json = kode.split(';');
				var i=0;
				//jQuery("#selkode").empty();
				jQuery.each(json,function(){
					var keyval = json[i].split(':');
					//jQuery("<option>").attr("value", keyval[0]).text(keyval[1]).appendTo("#selkode");
					jQuery("#ket").attr("value", keyval[2]);
					i++;
 				});
			}
			
			function roundNumber(num, dec) {
				var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
				return result;
			}
			
			<?php
				if($idsurat != "")
				{
			?>
					//getKodeSurat('<?php echo $row['idkode']; ?>');
			<?php
				}
				else
				{
			?>
					getKodeSurat('0');
			<?php
				}
			?>
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
			
		</style>
	</head>
<body>
<form name="frmsubmit" id="frmsubmit" method="post">
	<?php
	if($act != "del")
	{
	?>
	<div id="status_simpan" style="color:yellow;"></div>
	<table>
		<tr>
			<td>
				<table border="0" width="100%">
					<tr>
						<td width="72%"><b>KEMENTERIAN AGAMA</b></td>
						<td><b>NOMOR URUT :</b> <input name="no_disposisi" id="no_disposisi" size="10" maxlength="10" type="text" value="<?php echo isset($row['no_disposisi'])?$row['no_disposisi']:"1";?>"></td>
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
				<table width="100%" style="border-top: 1px solid">
					<tr>
						<td width="15%">Disampaikan Jam</td><td width="1%">:</td>
						<td><input name="jam_sampai" id="jam_sampai" size="12" maxlength="5" type="text" value="<?php echo isset($row['jam_sampai'])?$row['jam_sampai']:"";?>"></td>
					</tr>
					<tr>
						<td width="15%">Tanggal</td><td width="1%">:</td>
						<td><input name="tgl_sampai" id="tgl_sampai" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_sampai'])) {echo $myfunction->dateformatSlash(isset($row['tgl_sampai'])?$row['tgl_sampai']:date('d/m/Y'));}?>"></td>
					</tr>
				</table>
			</td>
		</tr>
		<HR>
		<tr>
			<td>
				<table style="border-style:solid;">
					<tr>
						<td>
							<table>
								<tr>
									<td>Indek</td>
									<td>:</td>
									<td>
										<select id="selkode" name="selkode" onchange="getKodeSurat(jQuery('#selkode').val(),jQuery('#sessid').val());">
										<?php
											$sqlkode = "SELECT k.idkode,LEFT(CONCAT(b.bagian,' (KODE:',k.kode,') : 	
												',k.hal),105) AS kode,k.hal FROM kodesurat k 
												INNER JOIN bagian b ON k.idbagian=b.idbagian";
											$resultkode = mysql_query( $sqlkode ) or die("Could not execute query.".mysql_error());
											while($rowkode = mysql_fetch_array($resultkode,MYSQL_ASSOC)) 
											{
												if($idsurat != "")
												{
										?>
												<option value="<?php echo $rowkode['idkode'];?>" <?php if($row['idkode']==$rowkode['idkode']){echo 'selected="true"';$hal = $rowkode['hal'];}?>><?php echo $rowkode['kode'];?></option>
										<?php
												}
												else
												{
										?>
												<option value="<?php echo $rowkode['idkode'];?>"><?php echo $rowkode['kode'];?></option>
										<?php
												}
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Hal</td>
									<td>:</td>
									<td><textarea name="ket" id="ket" rows="2" cols="106" disabled="yes" style="resize:none;"><?php echo $hal;?></textarea></td>
								</tr>
								<tr>
									<td>Berkas</td>
									<td>:</td>
									<td><input name="berkas" id="berkas" size="10" maxlength="10" type="text" value="<?php echo isset($row['berkas'])?$row['berkas']:"";?>"></td>
								</tr>
							</table>
							<HR>
							<table>
								<tr>
									<td>Tanggal/Nomor</td>
									<td>:</td>
									<td><input name="tgl_surat" id="tgl_surat" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_surat'])) {echo $myfunction->dateformatSlash(isset($row['tgl_surat'])?$row['tgl_surat']:date('d/m/Y'));}?>">
									</td>
									<td>/&nbsp;<input name="no_surat" id="no_surat" size="79" maxlength="50" type="text" value="<?php echo isset($row['no_surat'])?$row['no_surat']:"";?>">
									</td>
								</tr>
								<tr>
									<td>Asal</td>
									<td>:</td>
									<td colspan="2"><input name="pengirim" id="pengirim" size="100" maxlength="150" type="text" value="<?php echo isset($row['pengirim'])?$row['pengirim']:"";?>"></td>
								</tr>
								<tr>
									<td>Isi Ringkas</td>
									<td>:</td>
									<td colspan="2"><input name="perihal" id="perihal" size="100" maxlength="250" type="text" value="<?php echo isset($row['perihal'])?$row['perihal']:"";?>"></td>
								</tr>
								<tr>
									<td>Diterima Tgl</td>
									<td>:</td>
									<td colspan="2"><input name="tgl_terima" id="tgl_terima" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_terima'])) {echo $myfunction->dateformatSlash(isset($row['tgl_terima'])?$row['tgl_terima']:date('d/m/Y'));}?>"></td>
								</tr>
							</table>
							<HR>
							<table>
								<tr>
									<td>Tanggal Penyelesaian</td>
									<td>:</td>
									<td><input name="tgl_selesai" id="tgl_selesai" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_selesai'])) {echo $myfunction->dateformatSlash(isset($row['tgl_selesai'])?$row['tgl_selesai']:date('d/m/Y'));}?>">
									</td>
								</tr>								
							</table>
							<HR>
							<table border="0" width="100%">
								<tr valign="top" align="left">
									<td width="75%"><p>Isi Disposisi :</p>
										<table border="0">
											<tr valign="top" align="left">
												<td>1. <textarea name="disposisi1" id="disposisi1" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi1'])?$row['disposisi1']:"";?></textarea></td>
											</tr>
											<tr valign="top" align="left">
												<td>2. <textarea name="disposisi2" id="disposisi2" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi2'])?$row['disposisi2']:"";?></textarea></td>
											</tr>
											<tr valign="top" align="left">
												<td>3. <textarea name="disposisi3" id="disposisi3" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi3'])?$row['disposisi3']:"";?></textarea></td>
											</tr>
											<tr valign="top" align="left">
												<td>4. <textarea name="disposisi4" id="disposisi4" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi4'])?$row['disposisi4']:"";?></textarea></td>
											</tr>
											<tr valign="top" align="left">
												<td>5. <textarea name="disposisi5" id="disposisi5" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi5'])?$row['disposisi5']:"";?></textarea></td>
											</tr>
											<tr valign="top" align="left">
												<td>6. <textarea name="disposisi6" id="disposisi6" rows="2" cols="75" style="resize:none;"><?php echo isset($row['disposisi6'])?$row['disposisi6']:"";?></textarea></td>
											</tr>
										</table>
									</td>
									<td width="1" bgcolor="#FFFFFF"><BR></td>
									<td width="24%"><p>Diteruskan Kepada :</p>
										<table border="0">
											<tr>
												<td height="25.9px">1.</td>
												<td height="25.9px"><input name="tujuan_disposisi1" id="tujuan_disposisi1" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi1'])?$row['tujuan_disposisi1']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi1" id="tgl_disposisi1" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi1'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi1'])?$row['tgl_disposisi1']:date('d/m/Y'));}?>"></td>
											</tr>
											<tr>
												<td height="25.9px">2.</td>
												<td height="25.9px"><input name="tujuan_disposisi2" id="tujuan_disposisi2" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi2'])?$row['tujuan_disposisi2']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi2" id="tgl_disposisi2" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi2'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi2'])?$row['tgl_disposisi2']:date('d/m/Y'));}?>"></td>
											</tr>
											<tr>
												<td height="25.9px">3.</td>
												<td height="25.9px"><input name="tujuan_disposisi3" id="tujuan_disposisi3" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi3'])?$row['tujuan_disposisi3']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi3" id="tgl_disposisi3" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi3'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi3'])?$row['tgl_disposisi3']:date('d/m/Y'));}?>"></td>
											</tr>
											<tr>
												<td height="25.9px">4.</td>
												<td height="25.9px"><input name="tujuan_disposisi4" id="tujuan_disposisi4" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi4'])?$row['tujuan_disposisi4']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi4" id="tgl_disposisi4" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi4'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi4'])?$row['tgl_disposisi4']:date('d/m/Y'));}?>"></td>
											</tr>
											<tr>
												<td height="25.9px">5.</td>
												<td height="25.9px"><input name="tujuan_disposisi5" id="tujuan_disposisi5" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi5'])?$row['tujuan_disposisi5']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi5" id="tgl_disposisi5" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi5'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi5'])?$row['tgl_disposisi5']:date('d/m/Y'));}?>"></td>
											</tr>
											<tr>
												<td height="25.9px">6.</td>
												<td height="25.9px"><input name="tujuan_disposisi6" id="tujuan_disposisi6" size="15" maxlength="25" type="text" value="<?php echo isset($row['tujuan_disposisi6'])?$row['tujuan_disposisi6']:"";?>"></td>
											</tr>
											<tr>
												<td height="25.9px"></td>
												<td height="25.9px"><input name="tgl_disposisi6" id="tgl_disposisi6" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_disposisi6'])) {echo $myfunction->dateformatSlash(isset($row['tgl_disposisi6'])?$row['tgl_disposisi6']:date('d/m/Y'));}?>"></td>
											</tr>
										</table>
									</td>
								</tr>								
							</table>
							<HR>
							<table>
								<tr>
									<td colspan="3">Sesudah digunakan harap segera dikembalikan</td>
								</tr>								
								<tr>
									<td>Kepada</td>
									<td>:</td>
									<td><input name="tujuan_akhir" id="tujuan_akhir" size="105" maxlength="100" type="text" value="<?php echo isset($row['tujuan_akhir'])?$row['tujuan_akhir']:"";?>">
									</td>
								</tr>								
								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td><input name="tgl_akhir" id="tgl_akhir" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_akhir'])) {echo $myfunction->dateformatSlash(isset($row['tgl_akhir'])?$row['tgl_akhir']:date('d/m/Y'));}?>">
									</td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%">Tanda tangan dan Nama terang Penerima</td><td width="1%">:</td>
						<td><input name="nama_penerima" id="nama_penerima" size="50" maxlength="50" type="text" value="<?php echo isset($row['nama_penerima'])?$row['nama_penerima']:"";?>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php
	}
	else
	{
		echo "Apakah Data Surat Masuk Ini Ingin Dihapus?";
	}
	?>
    <input type="hidden" name="FormName" value="disposisi">
	<input type="hidden" name="id" value="<?php echo $idsurat;?>">
	<input type="hidden" name="act" value="<?php echo $act;?>">
	<input type="hidden" name="sessid" value="<?php echo $sessid;?>">
	<input type="hidden" name="dontclose" id="dontclose" value="0">
</form>
</body>
</html>