<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

	$hal = "";
	$kode = "";
	$namafile = "";
	$listfile = "";
    $idsurat = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:"";
	$sessid = isset($_REQUEST['sessid'])?$_REQUEST['sessid']:"";
	//echo "files:".$_FILES['fileloc']."<br>";
    if(isset($_REQUEST['FormName']) && $_REQUEST['FormName']=="suratkeluar")
  	{
		if($act != "del")
		{
		$no_surat = $_POST['no_surat1']."/".$_POST['no_surat2']."/".$_POST['no_surat3']."/".$_POST['no_surat4']."/".$_POST['no_surat5'];
		$file = "";
		
		if (isset($_POST['namafile'])) 
		{
			$file = $_POST['namafile'];
		}
		
		if($idsurat!='')	//edit
  		{
			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
  			$query = "UPDATE suratkeluar SET tgl_surat='".$myfunction->dateformatSlashDb($_POST['tgl_surat'])."',no_surat='".$no_surat."',pengirim='".$_POST['pengirim']."',perihal='".$_POST['perihal']."',tujuan='".$_POST['tujuan']."',penandatangan='".$_POST['penandatangan']."',penyimpanan='".$_POST['penyimpanan']."',arsip=".$_POST['arsip'].",file_loc='".$file."',idkode=".$_POST['selkode'].",no_surat1='".$_POST['no_surat1']."',no_surat2='".$_POST['no_surat2']."',no_surat3='".$_POST['no_surat3']."',no_surat4='".$_POST['no_surat4']."',no_surat5='".$_POST['no_surat5']."',status_surat='".$_POST['status_surat']."',kondisi_surat='".$_POST['kondisi_surat']."',lampiran='".$_POST['lampiran']."' WHERE idsuratkeluar=".$idsurat;
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
  		}
  		else //add
		{
  			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
	
  			$query = "INSERT INTO suratkeluar(tgl_surat,no_surat,pengirim,perihal,tujuan,penandatangan,penyimpanan,arsip,file_loc,idkode,no_surat1,no_surat2,no_surat3,no_surat4,no_surat5,status_surat,kondisi_surat,lampiran) VALUES('".$myfunction->dateformatSlashDb($_POST['tgl_surat'])."','".$no_surat."','".$_POST['pengirim']."','".$_POST['perihal']."','".$_POST['tujuan']."','".$_POST['penandatangan']."','".$_POST['penyimpanan']."',".$_POST['arsip'].",'".$file."',".$_POST['selkode'].",'".$_POST['no_surat1']."','".$_POST['no_surat2']."','".$_POST['no_surat3']."','".$_POST['no_surat4']."','".$_POST['no_surat5']."','".$_POST['status_surat']."','".$_POST['kondisi_surat']."','".$_POST['lampiran']."')";
			//echo $query;
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
  		}
		}
		else	//delete
		{
			$SQL = "DELETE FROM suratkeluar WHERE idsuratkeluar = ".$idsurat;
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
		<!--script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script-->
		<script src="js/date.js" type="text/javascript"></script>
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<script src="js/jquery.datePicker.js" type="text/javascript"></script>
		<script src="js/ajaxupload.3.5.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.date-pick').datePicker({autoFocusNextInput: false,startDate:'01/01/2000'});
				jQuery("#selkode").focus();
				jQuery('#selkode').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_surat").focus();
					}
					if(e.keyCode==38){	//up
						getKodeSurat(jQuery('#selkode').val());
					}
					if(e.keyCode==40){	//down
						getKodeSurat(jQuery('#selkode').val());
					}
				});
				jQuery('#tgl_surat').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat1").focus();
						getNoSurat();
					}
				});
				jQuery('#no_surat1').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat2").focus();
					}
				});
				jQuery('#no_surat2').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat3").focus();
						getTtdSurat();
					}
				});
				jQuery('#no_surat3').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat4").focus();
					}
				});
				jQuery('#no_surat4').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_surat5").focus();
					}
				});
				jQuery('#no_surat5').bind('keypress', function(e) {
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
						jQuery("#tujuan").focus();
					}
				});
				jQuery('#tujuan').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#penandatangan").focus();
					}
				});
				jQuery('#penandatangan').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#penyimpanan").focus();
					}
				});
				jQuery('#penyimpanan').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#arsip").focus();
					}
				});
				jQuery('#arsip').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#status_surat").focus();
					}
				});
				jQuery('#status_surat').bind('keypress', function(e) {
					if(e.keyCode==13){
						
						jQuery("#kondisi_surat").focus();
					}
				});
				jQuery('#kondisi_surat').bind('keypress', function(e) {
					if(e.keyCode==13){
						
						jQuery("#lampiran").focus();
					}
				});
				jQuery('#lampiran').bind('keypress', function(e) {
					if(e.keyCode==13){
						
						jQuery("#upload").focus();
					}
				});
				jQuery('#upload').bind('keypress', function(e) {
					if(e.keyCode==13){
						
						jQuery(":button:last").focus();
					}
				});
				<?php
					if($idsurat != "")
					{
						$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
						mysql_select_db($database) or die("Error conecting to db.");
						
						$SQL = "SELECT tgl_surat,no_surat,pengirim,perihal,tujuan,penandatangan,
							penyimpanan,arsip,file_loc,idkode,
							no_surat1,no_surat2,no_surat3,no_surat4,no_surat5,
							status_surat,kondisi_surat,lampiran 
							FROM suratkeluar
							WHERE idsuratkeluar = ".$idsurat;
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
									$listfile .= "<li id='li_".$j."'>".$arrnamafile[$j]." [<a href='#' onclick='hapusfile(".$j.");'>Hapus</a>]</li>";
								}
							}
							else
							{
								$listfile = "<li id='li_0'>".$namafile." [<a href='#' onclick='hapusfile(0);'>Hapus</a>]</li>";
							}
						}
					}
				?>
			});
			function getKodeSurat(idkode)
			{
				//if(idkode == '0')
				//	idkode = jQuery('#selkode').val();
				var sessid = jQuery('#sessid').val();
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
					jQuery("#no_surat3").attr("value", keyval[1]);
					jQuery("#ket").attr("value", keyval[2]);
					i++;
 				});
			}
			
			function getNoSurat()
			{
				var tgl = jQuery('#tgl_surat').val();
				var sessid = jQuery('#sessid').val();
				if(tgl != "")
				{
					var nosurat = jQuery.ajax({
						url: 'nosurat.php?tgl='+tgl+'&sessid='+sessid, 
						type:"POST",
						datatype: "json",
						async: false, 
						success: function(data, result) {
							if (!result) 
								alert('Data Surat Keluar Belum Ada.');
						}
					}).responseText;
					
					jQuery("#no_surat4").attr("value", nosurat);
				}
			}
			
			function getTtdSurat()
			{
				var ttd = jQuery('#no_surat2').val();
				var sessid = jQuery('#sessid').val();
				if(ttd != "")
				{
					var ttdsurat = jQuery.ajax({
						url: 'ttdsurat.php?ttd='+ttd+'&sessid='+sessid, 
						type:"POST",
						datatype: "json",
						async: false, 
						success: function(data, result) {
							if (!result) 
								alert('Data Penandatangan Surat Belum Ada.');
						}
					}).responseText;
					
					jQuery("#penandatangan").attr("value", ttdsurat);
				}
			}
			
			function setFileLoc()
			{
				var namafile = jQuery("input[type=file]").val();
				jQuery("#namafile").val(namafile);
			}
			
			function hapusfile(idx)
			{
				jQuery("#li_"+idx).remove();
				var i = 0;
				if(jQuery("li[id*=li_]").length > 0)
				{
					jQuery("li[id*=li_]").each(function(index){
						var filename = $(this).text().replace("[Hapus]","");
						if(i>0)
						{
							var namafile = jQuery("#namafile").val();
							jQuery("#namafile").val(namafile+";"+filename);
						}
						else
						{
							jQuery("#namafile").val(filename);
						}
						i++;
					});
				}
				else
				{
					jQuery("#namafile").val("");
				}
			}
			
			$(function(){
				var btnUpload=$('#upload');
				var status=$('#status');
				var sessid = jQuery('#sessid').val();
				new AjaxUpload(btnUpload, {
					action: 'upload-file.php?sessid='+sessid,
					name: 'uploadfile',
					onSubmit: function(file, ext){
						/*if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
							// extension is not allowed 
							status.text('Only JPG, PNG or GIF files are allowed');
							return false;
						}*/
						status.text('Uploading...');
					},
					onComplete: function(file, response){
						//On completion clear the status
						status.text('');
						//Add uploaded file to list
						if(response==="success"){
							var filecnt = jQuery("#filecount").val();
							filecnt++;
							jQuery("#filecount").val(filecnt);
							$('<li id="li_'+filecnt+'"></li>').appendTo('#files').html(file+' [<a href="#" onclick="hapusfile('+filecnt+');">Hapus</a>]').addClass('success');
							var filename = jQuery("#namafile").val();
							if(filename != "")
							{
								jQuery("#namafile").val(filename+';'+file);
							}
							else
							{
								jQuery("#namafile").val(file);
							}
						} else{
							$('<li></li>').appendTo('#files').text(file).addClass('error');
						}
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
<form name="frmsubmit" id="frmsubmit" method="post" enctype="multipart/form-data" action="frmsuratkeluar.php">
	<?php
	if($act != "del")
	{
	?>
	<div id="status_simpan" style="color:yellow;"></div>
	<table>
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
										<select id="selkode" name="selkode" onchange="getKodeSurat(jQuery('#selkode').val());">
										<?php
											$k=0;
											$sqlkode = "SELECT k.idkode,k.kode AS kodesurat,CONCAT(b.bagian,' (KODE:',k.kode,')') AS kode,k.hal FROM kodesurat k INNER JOIN bagian b ON k.idbagian=b.idbagian";
											$resultkode = mysql_query( $sqlkode ) or die("Could not execute query.".mysql_error());
											while($rowkode = mysql_fetch_array($resultkode,MYSQL_ASSOC)) 
											{
												if($idsurat != "")
												{
										?>
												<option value="<?php echo $rowkode['idkode'];?>" <?php if($row['idkode']==$rowkode['idkode']){echo 'selected="true"';$hal = $rowkode['hal'];$kode = $rowkode['kodesurat'];}?>><?php echo $rowkode['kode'];?></option>
										<?php
												}
												else
												{
										?>
												<option value="<?php echo $rowkode['idkode'];?>"><?php echo $rowkode['kode'];?></option>
										<?php
													if($k == 0)
													{
														$hal = $rowkode['hal'];
														$kode = $rowkode['kodesurat'];
													}
													$k++;
												}
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Hal</td>
									<td>:</td>
									<td><textarea name="ket" id="ket" rows="2" cols="60" disabled="yes" style="resize:none;"><?php echo $hal;?></textarea>
									</td>
								</tr>
								<tr>
									<td>Tanggal Surat</td>
									<td>:</td>
									<td><input name="tgl_surat" id="tgl_surat" size="12" maxlength="10" type="text" class="date-pick" value="<?php if(isset($row['tgl_surat'])) {echo $myfunction->dateformatSlash(isset($row['tgl_surat'])?$row['tgl_surat']:date('d/m/Y'));}?>">
									</td>
								</tr>
								<tr>
									<td>Nomor Surat</td>
									<td>:</td>
									<td><input name="no_surat1" id="no_surat1" size="5" maxlength="6" type="text" value="<?php echo isset($row['no_surat1'])?$row['no_surat1']:"Sti.02";?>">/<input name="no_surat2" id="no_surat2" size="2" maxlength="2" type="text" value="<?php echo isset($row['no_surat2'])?$row['no_surat2']:"";?>">/<input name="no_surat3" id="no_surat3" size="8" maxlength="10" type="text" value="<?php echo isset($row['no_surat3'])?$row['no_surat3']:$kode;?>">/<input name="no_surat4" id="no_surat4" size="8" maxlength="8" type="text" value="<?php echo isset($row['no_surat4'])?$row['no_surat4']:"";?>">/<input name="no_surat5" id="no_surat5" size="4" maxlength="4" type="text" value="<?php echo isset($row['no_surat5'])?$row['no_surat5']:date('Y');?>">
									</td>
								</tr>
								<tr>
									<td>Pengirim</td>
									<td>:</td>
									<td><input name="pengirim" id="pengirim" size="40" maxlength="150" type="text" value="<?php echo isset($row['pengirim'])?$row['pengirim']:"";?>"></td>
								</tr>
								<tr>
									<td>Perihal</td>
									<td>:</td>
									<td><input name="perihal" id="perihal" size="40" maxlength="250" type="text" value="<?php echo isset($row['perihal'])?$row['perihal']:"";?>"></td>
								</tr>
								<tr>
									<td>Tujuan</td>
									<td>:</td>
									<td><input name="tujuan" id="tujuan" size="40" maxlength="150" type="text" value="<?php echo isset($row['tujuan'])?$row['tujuan']:"";?>"></td>
								</tr>
								<tr>
									<td>Penandatangan</td>
									<td>:</td>
									<td><input name="penandatangan" id="penandatangan" size="40" maxlength="150" type="text" value="<?php echo isset($row['penandatangan'])?$row['penandatangan']:"";?>"></td>
								</tr>
								<tr>
									<td>Penyimpanan</td>
									<td>:</td>
									<td>
										<select id="penyimpanan" name="penyimpanan">
											<option value="File" <?php 
												if(isset($row)){if($row['penyimpanan']=='File'){echo 'selected="true"';}}?>>File</option>
											<option value="Kabinet" <?php 
												if(isset($row)){if($row['arsip']=='Kabinet'){echo 'selected="true"';}}?>>Kabinet</option>
											<option value="Lemari" <?php 
												if(isset($row)){if($row['arsip']=='Lemari'){echo 'selected="true"';}}?>>Lemari</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Arsip</td>
									<td>:</td>
									<td>
										<select id="arsip" name="arsip">
											<option value="0" <?php 
												if(isset($row)){if($row['arsip']=='0'){echo 'selected="true"';}}?>>Tidak Ada</option>
											<option value="1" <?php 
												if(isset($row)){if($row['arsip']=='1'){echo 'selected="true"';}}?>>Ada</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Status Surat</td>
									<td>:</td>
									<td>
										<select id="status_surat" name="status_surat">
											<option value="Biasa" <?php 
												if(isset($row)){if($row['status_surat']=='Biasa'){echo 'selected="true"';}}?>>Biasa</option>
											<option value="Rahasia" <?php 
												if(isset($row)){if($row['status_surat']=='Rahasia'){echo 'selected="true"';}}?>>Rahasia</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Kondisi Surat</td>
									<td>:</td>
									<td>
										<select id="kondisi_surat" name="kondisi_surat">
											<option value="Asli" <?php 
												if(isset($row)){if($row['kondisi_surat']=='Asli'){echo 'selected="true"';}}?>>Asli</option>
											<option value="Fotocopy" <?php 
												if(isset($row)){if($row['kondisi_surat']=='Fotocopy'){echo 'selected="true"';}}?>>Fotocopy</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Jumlah Lampiran</td>
									<td>:</td>
									<td><input name="lampiran" id="lampiran" size="10" maxlength="5" type="text" value="<?php echo isset($row['lampiran'])?$row['lampiran']:"";?>"></td>
								</tr>
								<tr>
									<td>Hasil Scan Surat</td>
									<td>:</td>
									<td>
										<div id="upload">
											<span>Upload File<span>
										</div>
										<span id="status"></span>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><ul id="files"><?php echo $listfile;?></ul></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php
	}
	else
	{
		echo "Apakah Data Surat Keluar Ini Ingin Dihapus?";
	}
	?>
    <input type="hidden" name="FormName" value="suratkeluar">
	<input type="hidden" name="id" value="<?php echo $idsurat;?>">
	<input type="hidden" name="act" value="<?php echo $act;?>">
	<input type="hidden" name="dontclose" id="dontclose" value="0">
	<input type="hidden" name="sessid" value="<?php echo $sessid;?>">
	<input type="hidden" name="filecount" id="filecount" value="0">
	<input type="hidden" name="namafile" id="namafile" value="<?php echo $namafile;?>">
</form>
</body>
</html>