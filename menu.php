<?php
	require_once("checklogin.php");
?>
<ul>
    <li><a id="menu_disposisi_masuk" href="#" onclick="getDisposisi();">Surat Masuk</a></li>
    <li><a id="menu_disposisi" href="#" onclick="getSuratKeluar();">Surat Keluar</a></li>
	<li id="menu_laporan">
		<a href='#'>Laporan</a>
		<ul style='z-index:2;'>
			<li><a href='#'>Laporan Per Bulanan</a>
				<ul style='z-index:2;'>
					<li><a href='#' onclick="getSuratMasukBln();">Surat Masuk</a></li>
					<li><a href='#' onclick="getSuratKeluarBln();">Surat Keluar</a></li>
				</ul>
			</li>
			<li><a href='#'>Laporan Per Indeks</a>
				<ul style='z-index:2;'>
					<li><a href='#' onclick="getSuratMasukIndex();">Surat Masuk</a></li>
					<li><a href='#' onclick="getSuratKeluarIndex();">Surat Keluar</a></li>
				</ul>
			</li>
		</ul>
	</li>
    <li><a id="menu_kode" href="#" onclick="getKode();">Daftar Kode Surat</a></li>
    <li><a id="menu_ttd" href="#" onclick="getTtd();">Indek Penandatangan Surat</a></li>
	<li id="menu_setting" style="display:none">
		<a href='#'>Setting</a>
		<ul style='z-index:2;'>
			<li><a href='#' onclick="getUser();">Data Pengguna</a></li>
			<li><a href='#' onclick="getUserLevel();">Data Tingkat Pengguna</a></li>
			<li><a href='#' onclick="getUserAccess();">Data Akses Pengguna</a></li>
		</ul>
	</li>
    <li><a id="menu_login" href="#" onclick="getAdmin();">Logout</a></li>
    <li id="loginname"></li>
</ul>