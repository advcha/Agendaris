_menuCloseDelay=400           // The time delay for menus to remain visible on mouse out
_menuOpenDelay=0            // The time delay before menus open on mouse over
_subOffsetTop=0              // Sub menu top offset
_subOffsetLeft=0            // Sub menu left offset

/// Style Definitions ///

with(subStyle=new mm_style()){
onbgcolor="#9EE3A9";
oncolor="#1D3B23";
offbgcolor="#61A76D";
offcolor="#F7F9F7";
bordercolor="#367E45";
borderstyle="solid";
borderwidth=1;
separatorcolor="#325235";
separatorsize=2;
padding=4;
fontsize="90%";
fontstyle="bold";
fontfamily="Verdana, Tahoma, Arial";
subimage="./images/submenu_arrow_right-off.gif";
onsubimage="./images/submenu_arrow_right-on.gif";
subimagepadding="0 0 0 0";
headerbgcolor="#548959";
headercolor="#f2f2ff";
itemwidth=220;
zindex=1000;
}

/// Submenu Definitions ///

with(milonic=new menuname("sub1")){
style=subStyle;
aI("text=PERKARA PERDATA;showmenu=sub1.1;");
aI("text=PERKARA PIDANA;showmenu=sub1.2;");
aI("text=PENGADILAN HUBUNGAN INDUSTRIAL (PHI);showmenu=sub1.3;");
}

with(milonic=new menuname("sub1.1")){
style=subStyle;
aI("text=DAFTAR PERKARA PERDATA;showmenu=sub1.1.1;");
aI("text=DATA PERKARA PERDATA;showmenu=sub1.1.2;");
aI("text=AGENDA PERKARA PERDATA;url=agendaperdata.php;");
aI("text=KEADAAN PERKARA PERDATA;url=keadaanperdata.php;");
aI("text=STATISTIK PERKARA PERDATA;url=statistikperdata.php;");
aI("text=CARI DATA PERKARA PERDATA;url=cariperdata.php;");
}

with(milonic=new menuname("sub1.1.1")){
style=subStyle;
aI("text=REGISTER INDUK PERKARA PERDATA PERMOHONAN;url=regdaftarperdatapermohonan.php;");
aI("text=REGISTER INDUK PERKARA PERDATA GUGATAN;url=regdaftarperdatagugatan.php;");
aI("text=REGISTER PERMOHONAN PERKARA PERDATA BANDING;url=regdaftarperdatabanding.php;");
aI("text=REGISTER PERMOHONAN PERKARA PERDATA KASASI;url=regdaftarperdatakasasi.php;");
aI("text=REGISTER PERMOHONAN PENINJAUAN KEMBALI;url=regdaftarperdatapeninjauan.php;");
aI("text=REGISTER EKSEKUSI;url=regperdataeksekusijsp;");
}

with(milonic=new menuname("sub1.1.2")){
style=subStyle;
aI("text=REGISTER INDUK PERKARA PERDATA PERMOHONAN;url=regperdatapermohonan.php;");
aI("text=REGISTER INDUK PERKARA PERDATA GUGATAN;url=regperdatagugatan.php;");
aI("text=REGISTER PERMOHONAN PERKARA PERDATA BANDING;url=regperdatabanding.php;");
aI("text=REGISTER PERMOHONAN PERKARA PERDATA KASASI;url=regperdatakasasi.php;");
aI("text=REGISTER PERMOHONAN PENINJAUAN KEMBALI;url=regperdatapeninjauan.php;");
aI("text=REGISTER EKSEKUSI;url=regperdataeksekusijsp;");
}

with(milonic=new menuname("sub1.2")){
style=subStyle;
aI("text=DAFTAR PERKARA PIDANA;showmenu=sub1.2.1;");
aI("text=AGENDA PERKARA PIDANA;url=agendapidana.php;");
aI("text=KEADAAN PERKARA PIDANA;url=keadaanpidana.php;");
aI("text=STATISTIK PERKARA PIDANA;url=statistikpidana.php;");
aI("text=CARI DATA PERKARA PIDANA;url=caripidana.php;");
}

with(milonic=new menuname("sub1.2.1")){
style=subStyle;
aI("text=REGISTER INDUK PERKARA PIDANA BIASA;url=regpidanabiasa.php;");
aI("text=REGISTER PERKARA TINDAK PIDANA RINGAN;url=regpidanaringan.php;");
aI("text=REGISTER PERKARA PIDANA CEPAT;url=regpidanacepat.php;");
aI("text=REGISTER PERKARA LALU LINTAS;url=regpidanalalin.php;");
aI("text=REGISTER IJIN PENGGELEDAHAN;url=regpidanapenggeledahan.php;");
aI("text=REGISTER IJIN PENYITAAN;url=regpidanapenyitaan.php;");
aI("text=REGISTER BARANG BUKTI;url=regpidanabarangbukti.php;");
}

with(milonic=new menuname("sub1.3")){
style=subStyle;
aI("text=DAFTAR PERKARA PHI;url=daftarphi.php;");
aI("text=AGENDA PERKARA PHI;url=agendaphi.php;");
aI("text=KEADAAN PERKARA PHI;url=keadaanphi.php;");
aI("text=STATISTIK PERKARA PHI;url=statistikphi.php;");
aI("text=CARI DATA PERKARA PHI;url=cariphi.php;");
}

with(milonic=new menuname("sub2")){
style=subStyle;
aI("text=DAFTAR PEGAWAI/HAKIM;url=daftarpegawai.php;");
aI("text=MUTASI PEGAWAI;url=mutasipegawai.php;");
aI("text=ABSENSI PEGAWAI;showmenu=sub2.3;");
aI("text=CARI DATA PEGAWAI;url=caripegawai.php;");
aI("text=ADMINISTRASI;showmenu=sub2.1;");
}

with(milonic=new menuname("sub2.1")){
style=subStyle;
aI("text=TAMBAH PEGAWAI;url=addpegawai.php;");
aI("text=UBAH DATA PEGAWAI;url=editpegawai.php;");
aI("text=HAPUS PEGAWAI;url=delpegawai.php;");
}

with(milonic=new menuname("sub2.3")){
style=subStyle;
aI("text=LAPORAN ABSENSI HARI INI;url=page1.htm;");
aI("text=LAPORAN ABSENSI PER BAGIAN;url=page2.htm;");
aI("text=LAPORAN ABSENSI REKAP BULANAN;url=page1.htm;");
aI("text=LAPORAN ABSENSI REKAP TRIWULAN;url=page1.htm;");
aI("text=LAPORAN ABSENSI REKAP SETAHUN;url=page1.htm;");
aI("text=CARI DATA ABSENSI;url=page1.htm;");
}

with(milonic=new menuname("sub3")){
style=subStyle;
aI("text=LAPORAN PERKARA PERDATA;showmenu=sub3.1;");
aI("text=LAPORAN PERKARA PIDANA;showmenu=sub3.2;");
aI("text=LAPORAN PERKARA PHI;showmenu=sub3.3;");
}

with(milonic=new menuname("sub3.1")){
style=subStyle;
aI("text=KEADAAN PERKARA;showmenu=sub3.1.1;");
aI("text=BANDING;showmenu=sub3.1.1;");
aI("text=KASASI;showmenu=sub3.1.1;");
aI("text=PENINJAUAN KEMBALI;showmenu=sub3.1.1;");
aI("text=EKSEKUSI;showmenu=sub3.1.1;");
aI("text=KEGIATAN HAKIM PERKARA;showmenu=sub3.1.1;");
aI("text=LAPORAN KEUANGAN PERKARA;showmenu=sub3.1.1;");
aI("text=LAPORAN JENIS PERKARA;showmenu=sub3.1.1;");
}

with(milonic=new menuname("sub3.2")){
style=subStyle;
aI("text=KEADAAN PERKARA;showmenu=sub3.1.1;");
aI("text=BANDING;showmenu=sub3.1.1;");
aI("text=KASASI;showmenu=sub3.1.1;");
aI("text=PENINJAUAN KEMBALI;showmenu=sub3.1.1;");
aI("text=GRASI/REMISI;showmenu=sub3.1.1;");
aI("text=KEGIATAN HAKIM PERKARA;showmenu=sub3.1.1;");
aI("text=LAPORAN PELAKSANAAN TUGAS HAKIM PENGAWAS & PENGAMAT;showmenu=sub3.1.1;");
aI("text=LAPORAN JENIS PERKARA;showmenu=sub3.1.1;");
}

with(milonic=new menuname("sub3.1.1")){
style=subStyle;
aI("text=LAPORAN BULANAN;url=page2.htm;");
aI("text=LAPORAN TAHUNAN;url=page2.htm;");
}

with(milonic=new menuname("sub6")){
style=subStyle;
aI("text=SIDANG PERKARA PERDATA;url=page2.htm;");
aI("text=SIDANG PERKARA PIDANA;url=page2.htm;");
aI("text=CARI JADWAL SIDANG;url=page2.htm;");
}

with(milonic=new menuname("sub7")){
style=subStyle;
aI("text=TAMBAH PENGGUNA;url=page2.htm;");
aI("text=UBAH PENGGUNA;url=page2.htm;");
aI("text=HAPUS PENGGUNA;url=page2.htm;");
}

with(milonic=new menuname("sub4")){
style=subStyle;
aI("text=TAMBAH DATA INVENTARIS;url=page3.htm;");
aI("text=UBAH DATA INVENTARIS;url=page2.htm;");
aI("text=HAPUS DATA INVENTARIS;url=page2.htm;");
}

with(milonic=new menuname("sub5")){
style=subStyle;
aI("text=DATA BUKU;url=page3.htm;");
aI("text=PEMINJAMAN BUKU;url=page2.htm;");
aI("text=PENGEMBALIAN BUKU;url=page2.htm;");
}

drawMenus();

