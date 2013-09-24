<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
		<script language='JavaScript'>
			var ajaxRequest;

			function getAjax() //fungsi untuk mengecek apakah browser mendukung AJAX
			{
				try
				{
					// Opera 8.0+, Firefox, Safari
					ajaxRequest = new XMLHttpRequest();
				}
				catch (e)
				{
					// Internet Explorer Browsers
					try
					{
						ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e) 
					{
						try
						{
							ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e)
						{
							// Something went wrong
							alert("Your browser broke!");
							return false;
						}
					}
				}
			}

			jQuery(document).ready(function(){
				var nama = "<?php echo $_SESSION['CompleteName'];?>";
				var userlvl = "<?php echo $_SESSION['UserLevel'];?>";
				if(userlvl=="1")	//level administrator
				{
					jQuery('li#menu_setting').attr('style','');
				}
				jQuery("li#loginname").append("<a href='#' style='color:yellow'>"+nama+"</a>");
				jQuery(".ui-widget").css("font-size","0.7em");
				jQuery('#userlvl').val(userlvl);
				jQuery('#adddata').val("<?php echo $_SESSION['AddData'];?>");
				jQuery('#editdata').val("<?php echo $_SESSION['EditData'];?>");
				jQuery('#deldata').val("<?php echo $_SESSION['DelData'];?>");
			});
			
			function getDisposisi()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Tanggal</td><td>:</td><td><select id='pilihtgl' name='pilihtgl'><option value='0'>Hari Ini (<?php echo date('d/m/Y'); ?>)</option><option value='1'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='2'>--Pilih Tanggal--</option></select><input type='button' value='Cari' onclick='loadTgl(0)'></td></tr></table>");
				var idxtgl = "0";
				loadSuratMasuk(idxtgl,"","");
			}
			
			function loadSuratMasuk(idxtgl,tglawal,tglakhir)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=disposisi&idxtgl='+idxtgl+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Tgl. Terima', 'Nomor Surat', 'Nomor Urut', 'Pengirim', 'Perihal', 'Disposisi I', 'Disposisi II', 'Disposisi III', 'Disposisi IV', 'Disposisi V', 'Disposisi VI', 'Ket.'],
					colModel:[
						{name:'idsurat',index:'s.idsurat', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'tgl_terima',index:'s.tgl_terima', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'no_surat',index:'s.no_surat', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Surat'}}},
						{name:'no_disposisi',index:'s.no_disposisi', width:50, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Disposisi'}}},
						{name:'pengirim',index:'s.pengirim', width:125, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pengirim'}}},
						{name:'perihal',index:'s.perihal', width:125, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Perihal'}}},
						{name:'disposisi1',index:'tujuan_disposisi1', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi I'}}},
						{name:'disposisi2',index:'tujuan_disposisi2', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi II'}}},
						{name:'disposisi3',index:'tujuan_disposisi3', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi III'}}},
						{name:'disposisi4',index:'tujuan_disposisi4', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi IV'}}},
						{name:'disposisi5',index:'tujuan_disposisi5', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi V'}}},
						{name:'disposisi6',index:'tujuan_disposisi6', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Disposisi VI'}}},
						{name:'ket_disposisi',index:'ket_disposisi', width:50, search:false}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 's.idsurat',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Masuk Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
						jQuery('#add_list').attr("title","Tambah Data Disposisi Surat Masuk");
						jQuery('#edit_list').attr("title","Edit Disposisi Surat Masuk");
						jQuery('#del_list').attr("title","Hapus Data Disposisi Surat Masuk");
						jQuery('#search_list').attr("title","Cari Data Disposisi Surat Masuk");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Disposisi Surat Masuk");
						jQuery(".ui-widget").css("font-size","0.7em");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu",
				editfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmdisposisi.php?id='+id+'&act=edit&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:670,height:550,modal:true,title:'Edit Data Disposisi Surat Masuk',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Simpan": function() { 
						var no_disposisi = jQuery("#no_disposisi").val();
						var tgl_surat = jQuery("#tgl_surat").val();
						var pengirim = jQuery("#pengirim").val();
						var perihal = jQuery("#perihal").val();
						var tgl_terima = jQuery("#tgl_terima").val();
						if(no_disposisi == "" || tgl_surat == "" || pengirim == "" || perihal == "" || tgl_terima == "")
						{
							if(no_disposisi == "")
							{
								alert("Nomor Urut Disposisi Belum Di Isi.");
								jQuery("#no_disposisi").focus();
							}
							else if(tgl_surat == "")
							{
								alert("Tanggal Surat Belum Di Isi.");
								jQuery("#tgl_surat").focus();
							}
							else if(pengirim == "")
							{
								alert("Asal Surat Belum Di Isi.");
								jQuery("#pengirim").focus();
							}
							else if(perihal == "")
							{
								alert("Isi Ringkas Surat Belum Di Isi.");
								jQuery("#perihal").focus();
							}
							else if(tgl_terima == "")
							{
								alert("Tanggal Terima Surat Belum Di Isi.");
								jQuery("#tgl_terima").focus();
							}
						}
						else
						{
							dataString = jQuery("#frmsubmit").serialize();
							jQuery.ajax({
								url: 'frmdisposisi.php?act=edit&sessid='+<?php echo $_SESSION["UserID"];?>, 
								type: "POST",
								data: dataString,
								datatype: "json"
							});
							jQuery(this).dialog("close"); 
							jQuery('#frmdisposisi').remove();
							jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
							jQuery("#list").trigger("reloadGrid");
						}
					} },
					});
				},
				addfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmdisposisi.php?act=add&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:670,height:550,modal:true,title:'Tambah Data Disposisi Surat Masuk',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Simpan": function() {
						var no_disposisi = jQuery("#no_disposisi").val();
						var jam_sampai = jQuery("#jam_sampai").val();
						var tgl_sampai = jQuery("#tgl_sampai").val();
						var tgl_surat = jQuery("#tgl_surat").val();
						var pengirim = jQuery("#pengirim").val();
						var perihal = jQuery("#perihal").val();
						var tgl_terima = jQuery("#tgl_terima").val();
						if(no_disposisi == "" || tgl_surat == "" || pengirim == "" || perihal == "" || tgl_terima == "")
						{
							if(no_disposisi == "")
							{
								alert("Nomor Urut Disposisi Belum Di Isi.");
								jQuery("#no_disposisi").focus();
							}
							else if(jam_sampai == "")
							{
								alert("Disampaikan Jam Belum Di Isi.");
								jQuery("#jam_sampai").focus();
							}
							else if(tgl_sampai == "")
							{
								alert("Tanggal Penyampaian Belum Di Isi.");
								jQuery("#tgl_sampai").focus();
							}
							else if(tgl_surat == "")
							{
								alert("Tanggal Surat Belum Di Isi.");
								jQuery("#tgl_surat").focus();
							}
							else if(pengirim == "")
							{
								alert("Asal Surat Belum Di Isi.");
								jQuery("#pengirim").focus();
							}
							else if(perihal == "")
							{
								alert("Isi Ringkas Surat Belum Di Isi.");
								jQuery("#perihal").focus();
							}
							else if(tgl_terima == "")
							{
								alert("Tanggal Terima Surat Belum Di Isi.");
								jQuery("#tgl_terima").focus();
							}
						}
						else
						{
							dataString = jQuery("#frmsubmit").serialize();
							jQuery.ajax({
								url: 'frmdisposisi.php?act=add&sessid='+<?php echo $_SESSION["UserID"];?>, 
								type: "POST",
								data: dataString,
								datatype: "json"
							});
							if(jQuery("#chkTutup").is(":checked")==false){
								jQuery(this).dialog("close"); 
								jQuery('#frmdisposisi').remove();
								jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
							}
							else
							{
								jQuery("#status_simpan").html("<b>Data Berhasil Disimpan</b>");
								var nodisp = jQuery("#no_disposisi").val();
								var tgl_sampai = jQuery("#tgl_sampai").val();
								var tgl_surat = jQuery("#tgl_surat").val();
								var tgl_terima = jQuery("#tgl_terima").val();
								var nextno = parseInt(nodisp)+1;
								jQuery("#no_disposisi").val(nextno);
								jQuery('#jam_sampai').val("");
								jQuery('#tgl_sampai').val(tgl_sampai);
								jQuery('#berkas').val("");
								jQuery('#tgl_surat').val(tgl_surat);
								jQuery('#no_surat').val("");
								jQuery('#pengirim').val("");
								jQuery('#perihal').val("");
								jQuery('#tgl_terima').val(tgl_terima);
								jQuery('#tgl_selesai').val("");
								jQuery('#disposisi1').val("");
								jQuery('#tujuan_disposisi1').val("");
								jQuery('#tgl_disposisi1').val("");
								jQuery('#disposisi2').val("");
								jQuery('#tujuan_disposisi2').val("");
								jQuery('#tgl_disposisi2').val("");
								jQuery('#disposisi3').val("");
								jQuery('#tujuan_disposisi3').val("");
								jQuery('#tgl_disposisi3').val("");
								jQuery('#disposisi4').val("");
								jQuery('#tujuan_disposisi4').val("");
								jQuery('#tgl_disposisi4').val("");
								jQuery('#disposisi5').val("");
								jQuery('#tujuan_disposisi5').val("");
								jQuery('#tgl_disposisi5').val("");
								jQuery('#disposisi6').val("");
								jQuery('#tujuan_disposisi6').val("");
								jQuery('#tgl_disposisi6').val("");
								jQuery('#tujuan_akhir').val("");
								jQuery('#tgl_akhir').val("");
								jQuery("#no_disposisi").focus();
							}
							jQuery("#list").trigger("reloadGrid");
						}
					} },
					open: function(){
						jQuery(".ui-dialog-buttonpane").append("<div id='tutupform'><input type='checkbox' id='chkTutup'>Jangan Tutup Setelah Simpan</input></div>");	
						jQuery("#chkTutup").click(function(){
							if(jQuery("#chkTutup").is(":checked")==false){
								jQuery("#dontclose").val("0");
							}
							else
							{
								jQuery("#dontclose").val("1");
							}
						});						
					}
					});
				},
				delfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmdisposisi.php?id='+id+'&act=del&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:350,height:150,modal:true,title:'Hapus Data Disposisi Surat Masuk',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Hapus": function() { 
						dataString = jQuery("#frmsubmit").serialize();
						jQuery.ajax({
							url: 'frmdisposisi.php?act=del&sessid='+<?php echo $_SESSION["UserID"];?>, 
							type: "POST",
							data: dataString,
							datatype: "json"
						});
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						jQuery("#list").trigger("reloadGrid");
					} },
					});
				}				
				},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Surat Masuk",
					msg: "Apakah Anda Yakin Data Surat Masuk Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 400
				},
				//search the fourth
				{
					caption: "Cari Data Surat Masuk",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Masuk",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_masuk','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Form Data Surat Masuk (Kertas Legal)",
					buttonicon:"ui-icon-newwin", 
					onClickButton: function(){ 
						var grid = jQuery("#list");
						var rowid = grid.jqGrid('getGridParam', 'selrow');
						/*var src = 'printdisposisi2.php?id='+rowid+'&sessid='+<?php echo $_SESSION["UserID"];?>;
						$.modal('<iframe src="' + src + '" height="550" width="800" style="border:1">', {
							closeHTML:"<a href='#'><img src='images/x.png' alt='Tutup' /></a>",
							containerCss:{
								backgroundColor:"#fff",
								borderColor:"#fff",
								height:550,
								padding:0,
								width:670
							},
							overlayClose:true
						});*/
						window.open('printdisposisi2.php?id='+rowid+'&sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						//jQuery(".count:contains('items selected')").replace("items selected","Kolom Di Tampilkan");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
						//jQuery(".remove-all").html("Hapus Semua Kolom");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Masuk Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_masuk','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getSuratKeluar()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Tanggal</td><td>:</td><td><select id='pilihtgl' name='pilihtgl'><option value='0'>Hari Ini (<?php echo date('d/m/Y'); ?>)</option><option value='1'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='2'>--Pilih Tanggal--</option></select><input type='button' value='Cari' onclick='loadTgl(1)'></td></tr></table>");
				/*var strcari = <?php echo date('Ymd'); ?>;*/
				var idxtgl = "0";
				loadSuratKeluar(idxtgl,"","");
			}
			
			function loadSuratKeluar(idxtgl,tglawal,tglakhir)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=suratkeluar&idxtgl='+idxtgl+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Tgl. Surat', 'Nomor Surat', 'Pengirim', 'Perihal', 'Tujuan', 'Penandatangan', 'Penyimpanan', 'Arsip', 'Status Surat', 'Kondisi Surat', 'Lampiran', 'Scan Surat', 'Ket.'],
					colModel:[
						{name:'idsuratkeluar',index:'idsuratkeluar', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'tgl_surat',index:'tgl_surat', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}, searchoptions:{sopt:['eq','le','ge'],dataInit:function(elem){jQuery(elem).datepicker({dateFormat: 'dd/mm/yy'});}}},
						{name:'no_surat',index:'no_surat', width:190, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'No. Surat Keluar'}}},
						{name:'pengirim',index:'pengirim', width:180, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Pengirim'}}},
						{name:'perihal',index:'perihal', width:180, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Perihal'}}},
						{name:'tujuan',index:'tujuan', width:180, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Tujuan Surat'}}},
						{name:'penandatangan',index:'penandatangan', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Penandatangan'}}},
						{name:'penyimpanan',index:'penyimpanan', width:100, editable:true, search:true, edittype:'select', editoptions: {size:50}, stype:'select',searchoptions:{sopt:['eq'],value:getPenyimpanan(0)}},
						{name:'arsip',index:'arsip', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Arsip'}}},
						{name:'status_surat',index:'status_surat', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Status Surat'}}},
						{name:'kondisi_surat',index:'kondisi_surat', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Kondisi Surat'}}},
						{name:'lampiran',index:'lampiran', width:80, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Lampiran'}}},
						{name:'scan',index:'scan', width:100, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Scan Surat'}}},
						{name:'ket_disposisi',index:'ket_disposisi', width:50, search:false}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idsuratkeluar',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Keluar Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
						jQuery('#add_list').attr("title","Tambah Data Surat Keluar");
						jQuery('#edit_list').attr("title","Edit Data Surat Keluar");
						jQuery('#del_list').attr("title","Hapus Data Surat Keluar");
						jQuery('#search_list').attr("title","Cari Data Surat Keluar");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Surat Keluar");
						jQuery(".ui-widget").css("font-size","0.7em");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu",
				editfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmsuratkeluar.php?id='+id+'&act=edit&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:475,height:550,modal:true,title:'Edit Data Disposisi Surat Keluar',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Simpan": function() { 
						var no_disposisi = jQuery("#no_disposisi").val();
						if(no_disposisi == "")
						{
							alert("Nomor Urut Disposisi Belum Di Isi.");
							jQuery("#no_disposisi").focus();
						}
						else
						{
							dataString = jQuery("#frmsubmit").serialize();
							jQuery.ajax({
								url: 'frmsuratkeluar.php?act=edit&sessid='+<?php echo $_SESSION["UserID"];?>, 
								type: "POST",
								data: dataString,
								datatype: "json"
							});
							jQuery(this).dialog("close"); 
							jQuery('#frmdisposisi').remove();
							jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
							jQuery("#list").trigger("reloadGrid");
						}
					} },
					});
				},
				addfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmsuratkeluar.php?act=add&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:475,height:550,modal:true,title:'Tambah Data Disposisi Surat Keluar',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Simpan": function() {
						var no_disposisi = jQuery("#no_disposisi").val();
						if(no_disposisi == "")
						{
							alert("Nomor Urut Disposisi Belum Di Isi.");
							jQuery("#no_disposisi").focus();
						}
						else
						{
							dataString = jQuery("#frmsubmit").serialize();
							jQuery.ajax({
								url: 'frmsuratkeluar.php?act=add&sessid='+<?php echo $_SESSION["UserID"];?>, 
								type: "POST",
								data: dataString,
								datatype: "json"
							});
							if(jQuery("#chkTutup").is(":checked")==false){
								jQuery(this).dialog("close"); 
								jQuery('#frmdisposisi').remove();
								jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
							}
							else
							{
								//clear form
								jQuery("#status_simpan").html("<b>Data Berhasil Disimpan</b>");
								//jQuery("#no_surat2").val("");
								//jQuery("#no_surat3").val("");
								jQuery("#no_surat4").val("");
								jQuery("#pengirim").val("");
								jQuery("#perihal").val("");
								jQuery("#tujuan").val("");
								jQuery("#lampiran").val("");
								jQuery("#files").html("");
								jQuery("#namafile").val("");
								jQuery("#selkode").focus();
								//jQuery(".ui-button-text-only").css("font-size","0.9em");
							}
							jQuery("#list").trigger("reloadGrid");
						}
					} },
					open: function(){
						jQuery(".ui-dialog-buttonpane").append("<div id='tutupform'><input type='checkbox' id='chkTutup'>Jangan Tutup Setelah Simpan</input></div>");	
						jQuery("#chkTutup").click(function(){
							if(jQuery("#chkTutup").is(":checked")==false){
								jQuery("#dontclose").val("0");
							}
							else
							{
								jQuery("#dontclose").val("1");
							}
						});						
					}
					});
				},
				delfunc: function(id){ 
					jQuery('#frmdisposisi').load('frmsuratkeluar.php?id='+id+'&act=del&sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:350,height:150,modal:true,title:'Hapus Data Disposisi Surat Keluar',
					buttons: { "Batal": function() {
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
					}, "Hapus": function() { 
						dataString = jQuery("#frmsubmit").serialize();
						jQuery.ajax({
							url: 'frmsuratkeluar.php?act=del&sessid='+<?php echo $_SESSION["UserID"];?>, 
							type: "POST",
							data: dataString,
							datatype: "json"
						});
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						jQuery("#list").trigger("reloadGrid");
					} },
					});
				}				
				},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Surat Keluar",
					msg: "Apakah Anda Yakin Data Surat Keluar Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 400
				},
				//search the fourth
				{
					caption: "Cari Data Surat Keluar",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Keluar",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_keluar','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"View Form Data Surat Keluar",
					buttonicon:"ui-icon-newwin", 
					onClickButton: function(){ 
						var grid = jQuery("#list");
						var rowid = grid.jqGrid('getGridParam', 'selrow');
						var src = 'viewsuratkeluar.php?id='+rowid+'&sessid='+<?php echo $_SESSION["UserID"];?>;
						$.modal('<iframe src="' + src + '" height="400" width="450" style="border:0">', {
							closeHTML:"<a href='#'><img src='images/x.png' alt='Tutup' /></a>",
							containerCss:{
								backgroundColor:"#fff",
								borderColor:"#fff",
								height:300,
								padding:0,
								width:450
							},
							overlayClose:true
						});
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Keluar Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_keluar','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getSuratMasukBln()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Bulan</td><td>:</td><td><select id='pilihbln' name='pilihbln'><option value='0'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='1'>--Pilih Bulan--</option></select><input type='button' value='Cari' onclick='loadBln(0)'></td></tr></table>");
				var strcari = "<?php echo date('Y-m'); ?>";
				var idxbln = "0";
				loadSuratMasukBln(idxbln,strcari);
			}
			
			function loadSuratMasukBln(idxbln,bln)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=suratmasukbln&idxbln='+idxbln+'&bln='+bln+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Indek', 'Tgl. Terima', 'Nomor Surat', 'Nomor Urut', 'Pengirim', 'Perihal', 'Disposisi I', 'Disposisi II', 'Disposisi III', 'Disposisi IV', 'Disposisi V', 'Disposisi VI', 'Ket.'],
					colModel:[
						{name:'idsurat',index:'s.idsurat', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'indek',index:'s.indek', width:125},
						{name:'tgl_terima',index:'s.tgl_terima', width:80, search:false, formatter:'date', formatoptions:{newformat:'d/m/Y'}},
						{name:'no_surat',index:'s.no_surat', width:100, search:false},
						{name:'no_disposisi',index:'s.no_disposisi', width:50, search:false},
						{name:'pengirim',index:'s.pengirim', width:125},
						{name:'perihal',index:'s.perihal', width:125},
						{name:'disposisi1',index:'disposisi1', width:100},
						{name:'disposisi2',index:'disposisi2', width:100},
						{name:'disposisi3',index:'disposisi3', width:100},
						{name:'disposisi4',index:'disposisi4', width:100},
						{name:'disposisi5',index:'disposisi5', width:100},
						{name:'disposisi6',index:'disposisi6', width:100},
						{name:'ket_disposisi',index:'ket_disposisi', width:50}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idsurat',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Masuk Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Masuk",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_masuk','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Masuk Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_masuk','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getSuratKeluarBln()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Bulan</td><td>:</td><td><select id='pilihbln' name='pilihbln'><option value='0'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='1'>--Pilih Bulan--</option></select><input type='button' value='Cari' onclick='loadBln(1)'></td></tr></table>");
				var strcari = "<?php echo date('Y-m'); ?>";
				var idxbln = "0";
				loadSuratKeluarBln(idxbln,strcari);
			}
			
			function loadSuratKeluarBln(idxbln,bln)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=suratkeluarbln&idxbln='+idxbln+'&bln='+bln+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Indek', 'Tgl. Surat', 'Nomor Surat', 'Pengirim', 'Perihal', 'Tujuan', 'Arsip', 'Status Surat', 'Kondisi Surat', 'Lampiran', 'Scan Surat', 'Ket.'],
					colModel:[
						{name:'idsuratkeluar',index:'idsuratkeluar', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'indek',index:'indek', width:125},
						{name:'tgl_surat',index:'tgl_surat', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}},
						{name:'no_surat',index:'no_surat', width:125},
						{name:'pengirim',index:'pengirim', width:180},
						{name:'perihal',index:'perihal', width:180},
						{name:'tujuan',index:'tujuan', width:180},
						{name:'arsip',index:'arsip', width:80},
						{name:'status_surat',index:'status_surat', width:80},
						{name:'kondisi_surat',index:'kondisi_surat', width:80},
						{name:'lampiran',index:'lampiran', width:80},
						{name:'file_loc',index:'file_loc', width:80},
						{name:'ket_disposisi',index:'ket_disposisi', width:50}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idsuratkeluar',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Keluar Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Keluar",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_keluar','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Keluar Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_keluar','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getSuratMasukIndex()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Bulan</td><td>:</td><td><select id='pilihbln' name='pilihbln' onchange='loadIdx(0);'><option value='0'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='1'>--Pilih Bulan--</option></select></td><td>Pilih Indek</td><td>:</td><td><select id='pilihidx' name='pilihidx'><?php 
					$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
					mysql_select_db($database) or die("Error conecting to db.");
					$sqlidx = "SELECT CAST(0 AS UNSIGNED) AS idbagian,'--Semua Indek' AS bagian
						UNION 
						SELECT DISTINCT b.idbagian,b.bagian FROM suratmasuk s
						INNER JOIN kodesurat k ON s.idkode=k.idkode
						INNER JOIN bagian b ON k.idbagian=b.idbagian
						WHERE SUBSTR(s.tgl_terima,1,7)='".date('Y-m')."'";
					$result = mysql_query( $sqlidx ) or die("Could not execute query.".mysql_error());
					while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
					{ 
						echo "<option value=".$row['idbagian'].">".$row['bagian']."</option>";
					}
				?></select><input type='button' value='Cari' onclick='loadDataIdx(0)'></td></tr></table>");
				var strcari = "<?php echo date('Y-m'); ?>";
				var idxbln = "0";
				var idxidx = "0";
				loadSuratMasukIndex(idxbln,strcari,idxidx);
			}
			
			function loadSuratMasukIndex(idxbln,bln,idxidx)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=suratmasukidx&idxbln='+idxbln+'&bln='+bln+'&idx='+idxidx+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Indek', 'Tgl. Terima', 'Nomor Surat', 'Nomor Urut', 'Pengirim', 'Perihal', 'Disposisi I', 'Disposisi II', 'Disposisi III', 'Disposisi IV', 'Disposisi V', 'Disposisi VI', 'Ket.'],
					colModel:[
						{name:'idsurat',index:'s.idsurat', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'indek',index:'s.indek', width:125},
						{name:'tgl_terima',index:'s.tgl_terima', width:80, search:false, formatter:'date', formatoptions:{newformat:'d/m/Y'}},
						{name:'no_surat',index:'s.no_surat', width:100, search:false},
						{name:'no_disposisi',index:'s.no_disposisi', width:50, search:false},
						{name:'pengirim',index:'s.pengirim', width:125},
						{name:'perihal',index:'s.perihal', width:125},
						{name:'disposisi1',index:'disposisi1', width:100},
						{name:'disposisi2',index:'disposisi2', width:100},
						{name:'disposisi3',index:'disposisi3', width:100},
						{name:'disposisi4',index:'disposisi4', width:100},
						{name:'disposisi5',index:'disposisi5', width:100},
						{name:'disposisi6',index:'disposisi6', width:100},
						{name:'ket_disposisi',index:'ket_disposisi', width:50}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idsurat',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Masuk Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Masuk",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_masuk','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Masuk Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_masuk','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getSuratKeluarIndex()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#divtgl").append("<table class='centertbl'><tr><td>Pilih Bulan</td><td>:</td><td><select id='pilihbln' name='pilihbln' onchange='loadIdx(1);'><option value='0'>Bulan Ini (<?php echo date('F Y'); ?>)</option><option value='1'>--Pilih Bulan--</option></select></td><td>Pilih Indek</td><td>:</td><td><select id='pilihidx' name='pilihidx'><?php 
					$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
					mysql_select_db($database) or die("Error conecting to db.");
					$sqlidx = "SELECT CAST(0 AS UNSIGNED) AS idbagian,'--Semua Indek' AS bagian
						UNION 
						SELECT DISTINCT b.idbagian,b.bagian FROM suratkeluar s
						INNER JOIN kodesurat k ON s.idkode=k.idkode
						INNER JOIN bagian b ON k.idbagian=b.idbagian
						WHERE SUBSTR(s.tgl_surat,1,7)='".date('Y-m')."'";
					$result = mysql_query( $sqlidx ) or die("Could not execute query.".mysql_error());
					while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
					{ 
						echo "<option value=".$row['idbagian'].">".$row['bagian']."</option>";
					}
				?></select><input type='button' value='Cari' onclick='loadDataIdx(1)'></td></tr></table>");
				var strcari = "<?php echo date('Y-m'); ?>";
				var idxbln = "0";
				var idxidx = "0";
				loadSuratKeluarIndex(idxbln,strcari,idxidx);
			}
			
			function loadSuratKeluarIndex(idxbln,bln,idxidx)
			{
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=suratkeluaridx&idxbln='+idxbln+'&bln='+bln+'&idx='+idxidx+'&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No.', 'Indek', 'Tgl. Surat', 'Nomor Surat', 'Pengirim', 'Perihal', 'Tujuan', 'Arsip', 'Status Surat', 'Kondisi Surat', 'Lampiran', 'Scan Surat', 'Ket.'],
					colModel:[
						{name:'idsuratkeluar',index:'idsuratkeluar', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:30, search:false},
						{name:'indek',index:'indek', width:125},
						{name:'tgl_surat',index:'tgl_surat', width:80, search:true, formatter:'date', formatoptions:{newformat:'d/m/Y'}},
						{name:'no_surat',index:'no_surat', width:125},
						{name:'pengirim',index:'pengirim', width:180},
						{name:'perihal',index:'perihal', width:180},
						{name:'tujuan',index:'tujuan', width:180},
						{name:'arsip',index:'arsip', width:80},
						{name:'status_surat',index:'status_surat', width:80},
						{name:'kondisi_surat',index:'kondisi_surat', width:80},
						{name:'lampiran',index:'lampiran', width:80},
						{name:'file_loc',index:'file_loc', width:80},
						{name:'ket_disposisi',index:'ket_disposisi', width:50}
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idsuratkeluar',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					width: 970,
					height: 'auto',
					shrinkToFit:false,
					caption: "Data Disposisi Surat Keluar Di STAIN Batusangkar",
					loadComplete: function(){ 
						jQuery('.ui-jqgrid .ui-jqgrid-htable th div').css("height","35px");
						jQuery(".ui-jqgrid tr.jqgrow td").css('white-space', 'normal !important'); 
						jQuery(".ui-jqgrid tr.jqgrow td").css('height', 'auto');
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:false,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Surat Keluar",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('surat_keluar','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Pilih Kolom Yang Ingin Di Tampilkan",
					buttonicon:"ui-icon-wrench", 
					onClickButton: function(){ 
						jQuery("#list").jqGrid("columnChooser",{
						caption:"Pilih Kolom Yang Ingin Di Tampilkan",
						bSubmit:"Tampilkan",
						bCancel:"Batal"});
						jQuery("#ui-dialog-title-colchooser_list").css("font-size","0.8em");
						jQuery(".ui-button-text").css("font-size","0.8em");
						var txt = jQuery(".count").text();
						var obj = txt.split(" ");
						jQuery(".count").html(obj[0]+" Kolom Di Tampilkan");
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Surat Keluar Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('surat_keluar','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getKode()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=kode&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Bagian', 'Hal', 'Kode'],
					colModel:[
						{name:'idkode',index:'k.idkode', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'bagian',index:'b.idbagian', width:200, editable:true, search:true, edittype:'select', editoptions: {size:50}, stype:'select',searchoptions:{sopt:['eq'],value:getBagian(0)}},
						{name:'hal',index:'k.hal', width:500, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 250}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Hal'}}},		
						{name:'kode',index:'k.kode', width:100, editable:true, editrules:{required:true}, editoptions: {size: 10, maxlength: 10}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Kode'}}}		
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idkode',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=kode&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Daftar Kode Surat",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Kode Surat");
						jQuery('#edit_list').attr("title","Edit Kode Surat");
						jQuery('#del_list').attr("title","Hapus Kode Surat");
						jQuery('#search_list').attr("title","Cari Kode Surat");
						jQuery('#refresh_list').attr("title","Loading/Refresh Kode Surat");
						
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
						
						if(jQuery("#adddata").val()=="true")
						{
							if(jQuery("#divBagian").length == 0)
							{
								jQuery("#frmdisposisi").append("<div id='divBagian'><input type='button' id='btnBagian' value='Data Bagian' onclick='loadBagian()'/></div>");
							}
						}
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_bagian', form).show();
                        jQuery('#tr_hal', form).show();
                        jQuery('#tr_kode', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var kec = jQuery.ajax({
							type: "POST",
							url: 'bagian.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Kode Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','bagian', { editoptions: { value: kec} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Daftar Kode Surat",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_bagian', form).show();
                        jQuery('#tr_hal', form).show();
                        jQuery('#tr_kode', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var kec = jQuery.ajax({
							type: "POST",
							url: 'bagian.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Kode Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','bagian', { editoptions: { value: kec} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Daftar Kode Surat",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Kode Surat ",
					msg: "Apakah Anda Yakin Data Kode Surat Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{
					recreateForm: true,
					caption: "Cari Data Kode Surat",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Kode Surat",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('kode_surat','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Kode Surat Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
						exportExcel('kode_surat','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getTtd()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=ttd&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'No Indek', 'Penandatangan', 'Jabatan'],
					colModel:[
						{name:'idttd',index:'idttd', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'idxttd',index:'idxttd', width:100, editable:true, search:true},
						{name:'namattd',index:'namattd', width:250, editable:true, search:true, searchoptions:{sopt:['cn'],attr:{title:'Penandatangan'}}},
						{name:'jabatan',index:'jabatan', width:250, editable:true, editrules:{required:true}, search:true, searchoptions:{sopt:['cn'],attr:{title:'Jabatan'}}}		
					],
					rowNum:25,
					rowList:[25,50,75,100,200],
					pager: '#page',
					sortname: 'idttd',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=ttd&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Daftar Indek Penandatangan Surat",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Indek Penandatangan Surat");
						jQuery('#edit_list').attr("title","Edit Indek Penandatangan Surat");
						jQuery('#del_list').attr("title","Hapus Indek Penandatangan Surat");
						jQuery('#search_list').attr("title","Cari Indek Penandatangan Surat");
						jQuery('#refresh_list').attr("title","Loading/Refresh Indek Penandatangan Surat");
						
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_idxttd', form).show();
                        jQuery('#tr_namattd', form).show();
                        jQuery('#tr_jabatan', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Indek Penandatangan Surat",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_idxttd', form).show();
                        jQuery('#tr_namattd', form).show();
                        jQuery('#tr_jabatan', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Indek Penandatangan Surat",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Indek Penandatangan Surat",
					msg: "Apakah Anda Yakin Data Indek Penandatangan Surat Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{
					recreateForm: true,
					caption: "Cari Data Indek Penandatangan Surat",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:true,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Print Data Indek Penandatangan Surat",
					buttonicon:"ui-icon-print", 
					onClickButton: function(){ 
						exportTable('kode_surat','printtable.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Indek Penandatangan Surat Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
						exportExcel('kode_surat','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getPenyimpanan(mode)
			{
				var penyimpanan = "File:File;Kabinet:Kabinet;Lemari:Lemari";
				
				if(mode==0)
				{
					return penyimpanan;
				}
				
			}
			
			function getBagian(mode)
			{
				var bagian = jQuery.ajax({
					url: 'bagian.php?search=true&sessid='+<?php echo $_SESSION["UserID"];?>, 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Bagian Belum Ada.');
					}
				}).responseText;
				
				if(mode==0)
				{
					return bagian;
				}
				
			}
			
			function loadBagian()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=bagian&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Bagian'],
					colModel:[
						{name:'idbagian',index:'idbagian', width:0, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'bagian',index:'bagian', width:500, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}, search:true, stype:'text', searchoptions:{sopt:['cn'],attr:{title:'Bagian'}}}	
					],
					rowNum:20,
					rowList:[20,40],
					pager: '#page',
					sortname: 'idbagian',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=bagian&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Data Bagian",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Bagian");
						jQuery('#edit_list').attr("title","Edit Data Bagian");
						jQuery('#del_list').attr("title","Hapus Data Bagian");
						jQuery('#search_list').attr("title","Cari Data Bagian");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Bagian");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:true,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_bagian', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Bagian",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
                        jQuery('#tr_bagian', form).show();
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Bagian",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Bagian",
					msg: "Apakah Anda Yakin Data Bagian Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{
					caption: "Cari Data Bagian",
					closeOnEscape: true,
					closeAfterSearch: true,
					Find: "Cari",
					Reset: "Reset",
					multipleSearch:false,
					overlay:false
				});
				jQuery("#list").jqGrid('navButtonAdd','#page',{
					caption:"", 
					title:"Export Data Bagian Ke Excel",
					buttonicon:"ui-icon-calculator", 
					onClickButton: function(){ 
					  exportExcel('bagian','csvExport.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					}
				});
				});
			}
			
			function getAdmin()
			{ 
				if(jQuery("a:#menu_login").html() == "Login")
				{
					jQuery(".ui-widget").css("font-size","0.8em");
					jQuery('#frmdisposisi').load('login.php?sessid='+<?php echo $_SESSION["UserID"];?>);
					jQuery('#frmdisposisi').dialog({width:300,height:'auto',modal:true,title:'Login Pengguna',
					buttons: { "Batal": function() {
						jQuery(".ui-widget").css("font-size","0.7em");
						jQuery(this).dialog("close"); 
						jQuery("#frmdisposisi").remove();
						jQuery("#frm").prepend("<div id='frmdisposisi'></div>");
					}, "Login": function() { 
						var pengguna = jQuery("#nama_pengguna").val();
						var pass = jQuery("#pass_pengguna").val();
						if(pengguna == "" || pass == "")
						{
							alert("Nama Pengguna dan Password Belum Di Isi.");
							jQuery("#nama_pengguna").focus();
						}
						else
						{
							var validateLogin = jQuery.ajax({
								url: 'login.php?pengguna='+pengguna+'&pass='+pass+'&sessid='+<?php echo $_SESSION["UserID"];?>, 
								type:"POST",
								datatype: "json",
								async: false, 
								success: function(data, result) {
									if (!result) 
										alert('Data Pengguna Belum Ada.');
								}
							}).responseText;
							//alert(validateLogin);
							var res = validateLogin.split(";");
							var result = res[0].split(":");
					
							if(result[0] == "result" && result[1] == "OK"){
								var nama = res[1].split(":");
								var userlvl = res[2].split(":");
								var adddata = res[3].split(":");
								var editdata = res[4].split(":");
								var deldata = res[5].split(":");
								if(userlvl[1]==1)	//level administrator
								{
									jQuery('li#menu_setting').attr('style','');
								}
								jQuery('#userlvl').val(userlvl[1]);
								jQuery('#adddata').val(adddata[1]);
								jQuery('#editdata').val(editdata[1]);
								jQuery('#deldata').val(deldata[1]);
								jQuery("a:#menu_login").html("Logout");
								jQuery("li#loginname").append("<a href='#' style='color:yellow'>"+nama[1]+"</a>");
								jQuery(".ui-widget").css("font-size","0.7em");
								jQuery(this).dialog("close"); 
								//clear page
								jQuery("#divtgl").empty();
								jQuery("#frmdisposisi").remove();
								jQuery("#frm").prepend("<div id='frmdisposisi'></div>");
								jQuery("#list").jqGrid('GridUnload');
								jQuery("#list").empty();
								jQuery("#content").attr("style","height:350px");
							}
							else
							{
								alert(validateLogin);
							}
						}
					} },
					});
				}
				else	//Logout
				{
					/*jQuery.ajax({
						url: 'login.php?logout=true&sessid='+<?php echo $_SESSION["UserID"];?>, 
						type:"POST",
						datatype: "json",
						async: false, 
						success: function(data, result) {
							if (!result) 
								alert('Data Pengguna Belum Ada.');
						}
					});
					jQuery('#userlvl').val("");
					jQuery('#adddata').val("false");
					jQuery('#editdata').val("false");
					jQuery('#deldata').val("false");
					jQuery("a:#menu_login").html("Login");
					jQuery("li#menu_setting").attr("style","display:none");
					jQuery("li#loginname").html("");
					//clear page
					jQuery("#divtgl").empty();
					jQuery("#frmdisposisi").empty();
					jQuery("#list").jqGrid('GridUnload');
					jQuery("#list").empty();
					jQuery("#content").attr("style","height:350px");*/
					<?php session_destroy(); ?>
					location.href = 'index.php';
				}
			}
			
			function getUser()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=user&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Ubah Password', 'Nama Lengkap', 'Pengguna', 'Password Lama', 'Password Baru', 'Tingkatan', 'Status'],
					colModel:[
						{name:'iduser',index:'iduser', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'changepass',index:'changepass', width:1, editable:true, edittype:'checkbox', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'nama_lengkap',index:'nama_lengkap', width:150, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}},
						{name:'user',index:'user', width:100, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}},
						{name:'password',index:'password', width:1, editable:true, edittype:'password', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'password2',index:'password2', width:1, editable:true, edittype:'password', hidden:true, editrules:{edithidden:true}, search:false},
						{name:'userlevel',index:'userlevel', width:100, editable:true, edittype:'select', editoptions: {size:50}},
						{name:'status',index:'status', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(0)}}		
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduser',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=user&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Data Pengguna",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Pengguna");
						jQuery('#edit_list').attr("title","Edit Data Pengguna");
						jQuery('#del_list').attr("title","Hapus Data Pengguna");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Pengguna");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
						
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
						jQuery('#changepass').change(function(){changePass();});
                        jQuery('#password').attr("disabled","disabled");
                        jQuery('#password2').attr("disabled","disabled");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","35%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var userlevel = jQuery.ajax({
							type: "POST",
							url: 'userlevel.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Tingkatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','userlevel', { editoptions: { value: userlevel} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					beforeShowForm: function(form) {
						jQuery('#tr_changepass').hide();
						jQuery('#tr_password td.CaptionTD').html("Password");
						jQuery('#tr_password2 td.CaptionTD').html("Password (Lagi)");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","35%");
						jQuery('.FormElement').css("width","90%");
                    },
					beforeInitData: function(form) {
						var userlevel = jQuery.ajax({
							type: "POST",
							url: 'userlevel.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (!result) 
									alert('Data Tingkatan Belum Ada');
							}
						}).responseText;
						jQuery('#list').jqGrid('setColProp','userlevel', { editoptions: { value: userlevel} });
					},
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Pengguna",
					msg: "Apakah Anda Yakin Data Pengguna Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{});
				});
			}
			
			function getUserLevel()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=userlevel&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'No', 'Tingkatan'],
					colModel:[
						{name:'iduserlevel',index:'iduserlevel', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'userlevel',index:'userlevel', width:400, editable:true, editrules:{required:true}, editoptions: {size: 30, maxlength: 50}}	
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduserlevel',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=userlevel&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Data Tingkatan",
					loadComplete: function(){ 
						jQuery('#add_list').attr("title","Tambah Data Tingkatan");
						jQuery('#edit_list').attr("title","Edit Data Tingkatan");
						jQuery('#del_list').attr("title","Hapus Data Tingkatan");
						jQuery('#refresh_list').attr("title","Loading/Refresh Data Tingkatan");
						if(jQuery("#adddata").val()=="false")
							jQuery("#add_list").remove();
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
						if(jQuery("#deldata").val()=="false")
							jQuery("#del_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:true,del:true,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Tingkatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("add",response);
                    },
					addCaption: "Tambah Data Tingkatan",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					modal:true,
					resize:false,
					width: 400
                },
				//del the third
				{
					afterSubmit: function(response, postdata) {
						showResponse("del",response);
                    },
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					recreateForm: true, 
					caption: "Hapus Data Tingkatan",
					msg: "Apakah Anda Yakin Data Tingkatan Ini Ingin Di Hapus?",
					bSubmit: "Hapus",
					bCancel: "Batal",
					width: 350
				},
				//search the fourth
				{});
				});
			}
			
			function getUserAccess()
			{
				jQuery("#content").attr("style","");
				jQuery("#divtgl").empty();
				jQuery("#frmdisposisi").empty();
				jQuery("#list").jqGrid('GridUnload');
				jQuery(document).ready(function(){
					jQuery("#list").jqGrid({
					url:'server.php?type=useraccess&sessid='+<?php echo $_SESSION["UserID"];?>,
					datatype: "json",
					mtype: "POST",
					colNames:['ID', 'IDUserLevel', 'No', 'Tingkatan', 'Tambah Data', 'Edit Data', 'Hapus Data'],
					colModel:[
						{name:'iduseraccess',index:'iduseraccess', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'iduserlevel',index:'iduserlevel', width:1, editable:false, hidden:true, editrules:{required:true}, search:false},
						{name:'no',index:'no', width:50, search:false},
						{name:'userlevel',index:'userlevel', width:250, editable:true, editrules:{required:false}, editoptions: {size: 30, maxlength: 50}},	
						{name:'adddata',index:'adddata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}},		
						{name:'editdata',index:'editdata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}},		
						{name:'deldata',index:'deldata', width:100, editable:true, edittype:'select', editoptions:{value:getStatus(1)}}		
					],
					rowNum:10,
					rowList:[10,20],
					pager: '#page',
					sortname: 'iduseraccess',
					recordpos: 'left',
					viewrecords: true,
					sortorder: "asc",
					height: 'auto',
					editurl:'server.php?type=useraccess&sessid='+<?php echo $_SESSION["UserID"];?>,
					caption: "Data Tingkatan",
					loadComplete: function(){ 
						jQuery('#edit_list').attr("title","Edit Data Akses Pengguna");
						if(jQuery("#editdata").val()=="false")
							jQuery("#edit_list").remove();
				    }
				});
				jQuery("#list").jqGrid('navGrid','#page',{edit:true,add:false,del:false,search:false,refresh:true,position:'right',alertcap:"Peringatan",alerttext:"Pilih Data Terlebih Dahulu"},
				//edit form always first
				{
					beforeShowForm: function(form) {
                        jQuery('#userlevel').attr("disabled","disabled");
                    },
					afterShowForm: function(form) {
						jQuery('.CaptionTD').css("vertical-align","bottom");
						jQuery('.CaptionTD').css("width","20%");
						jQuery('.FormElement').css("width","90%");
                    },
					afterSubmit: function(response, postdata) {
						showResponse("edit",response);
                    },
					editCaption: "Edit Data Akses Pengguna",
					recreateForm: true, 
					bSubmit: "Simpan",
					bCancel: "Batal",
					bClose: "Tutup",
					saveData: "Simpan Data?",
					bYes : "Ya",
					bNo : "Tidak",
					bExit : "Batal",
					reloadAfterSubmit:true,
					closeAfterEdit:true,
					modal:true,
					resize:false,
					width: 400
				},
				//add the second
				{},
				//del the third
				{},
				//search the fourth
				{});
				});
			}
			
			function getStatus(mode)
			{
				var status = "";
				if(mode==0)
					status = "1:Aktif;0:Non Aktif";
				else
					status = "1:Bisa;0:Tidak Bisa";
				
				return status;
			}
			
			function changePass()
			{
				if(jQuery("#changepass").is(":checked"))
				{
					jQuery("#password").removeAttr("disabled");
					jQuery("#password2").removeAttr("disabled");
				}
				else
				{
					jQuery("#password").attr("disabled","disabled");
					jQuery("#password2").attr("disabled","disabled");
				}
			}
			
			function showResponse(mode,response)
			{
				if(response.responseText != "")
				{
					alert(response.responseText);
				}
				jQuery("#cData").click();
				if(mode=="del")
					jQuery("#eData").click();
				jQuery("#list").trigger("reloadGrid");
			}
			
			function exportExcel(typeinfo,url)
			{
				var mya=new Array();
				mya=jQuery("#list").jqGrid('getDataIDs');  // Get All IDs
				var data_h=jQuery("#list").jqGrid('getRowData',mya[0]);     // Get First row to get the labels
				var colNames=new Array(); 
				var ii=0;
				var html="";
				for (var i in data_h){
					if(jQuery("#list_"+i+"").css("display")!="none")
					{
						html=html+jQuery("#jqgh_"+i+"").text()+"\t";
						colNames[ii++]=i;
					}
				}    // capture col names
				html=html+"\n";  // output each row with end of line
				var rplc = new RegExp("\n");
				for(i=0;i<mya.length;i++)
				{
					data=jQuery("#list").jqGrid('getRowData',mya[i]); // get each row
					for(j=0;j<colNames.length;j++)
					{
						var val = data[colNames[j]].toString();
						if(val.search(rplc) != -1)
						{
							//val = val.replace(rplc," ");
							var valsplit = val.split(rplc);
							if(valsplit.length>0)
							{
								var valjoin = "";
								for(l=0;l<valsplit.length;l++)
								{
									valjoin += valsplit[l]+" ";
								}
								val = valjoin;
							}
						}
						html=html+" "+val.valueOf().replace(/<br\s*[\/]?>/gi," ")+"\t"; // output each column as tab delimited
					}
					html=html+"\n";  // output each row with end of line
					
				}
				html=html+"\n";  // end of line at the end
				document.forms[0].csvBuffer.value=html;
				document.forms[0].typeinfo.value=typeinfo;
				document.forms[0].method='POST';
				document.forms[0].action=url;//'csvExport.php';  // send it to server which will open this contents in excel file
				document.forms[0].target='_blank';
				document.forms[0].submit();
			}
			
			function exportTable(typeinfo,url)
			{
				var mya=new Array();
				mya=jQuery("#list").jqGrid('getDataIDs');  // Get All IDs
				
				var data_h=jQuery("#list").jqGrid('getRowData',mya[0]);     // Get First row to get the labels
				var colNames=new Array(); 
				var ii=0;
				var html="<table border='1' class='sofT'><thead>";
				for (var i in data_h){
					if(jQuery("#list_"+i+"").css("display")!="none")
					{
						html=html+"<td class='helpHed'>"+jQuery("#jqgh_"+i+"").text()+"</td>";
						colNames[ii++]=i;
					}
				}    // capture col names
				
				/*for(k=0;k<colNames.length;k++)
				{
					html=html+"<td>"+colNames[k]+"</td>"; // output each column as tab delimited
				}*/
				html=html+"</thead><tbody>";  // output each row with end of line
				var rplc = new RegExp("\n");
				for(i=0;i<mya.length;i++)
				{
					data=jQuery("#list").jqGrid('getRowData',mya[i]); // get each row
					html=html+"<tr>";
					for(j=0;j<colNames.length;j++)
					{
						var val = data[colNames[j]].toString();
						if(val.search(rplc) != -1)
						{
							//val = val.replace(rplc," ");
							var valsplit = val.split(rplc);
							if(valsplit.length>0)
							{
								var valjoin = "";
								for(l=0;l<valsplit.length;l++)
								{
									valjoin += valsplit[l]+" ";
								}
								val = valjoin;
							}
						}
						html=html+"<td>"+val.valueOf()+"</td>"; // output each column as tab delimited
					}
					html=html+"</tr>";  // output each row with end of line

				}
				html=html+"</tbody></table>";  // end of line at the end
				document.forms[0].csvBuffer.value=html;
				document.forms[0].typeinfo.value=typeinfo;
				document.forms[0].method='POST';
				document.forms[0].action=url;//'csvExport.php';  // send it to server which will open this contents in excel file
				document.forms[0].target='_blank';
				document.forms[0].submit();
			}
			
			function loadTgl(jenis)
			{
				idxtgl = jQuery("#pilihtgl").val();
				if(idxtgl < "2")
				{
					if(jenis == 0)
					{
						loadSuratMasuk(idxtgl,"","");
					}
					else
					{
						loadSuratKeluar(idxtgl,"","");
					}
				}
				else
				{
					$(function() {
						jQuery('#frmdisposisi').load('frmtgl.php?sessid='+<?php echo $_SESSION["UserID"];?>);
						jQuery('#frmdisposisi').dialog({width:225,modal:true,title:'Pilih Tanggal',
						buttons: { "Batal": function() {
							jQuery(this).dialog("close"); 
							jQuery('#frmdisposisi').remove();
							jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						}, "Cari": function() { 
							var tglawal = jQuery("#tgl_awal").val();
							var tglakhir = jQuery("#tgl_akhir").val();
							var arrtgl = tglawal.split("/");
							var tglawalthn = arrtgl[2];
							var tglawalbln = arrtgl[1];
							if(tglawalbln.length < 2)
								tglawalbln = '0'+arrtgl[1];
							var tglawaltgl = arrtgl[0];
							if(tglawaltgl.length < 2)
								tglawaltgl = '0'+arrtgl[0];
							var arrtglawal = tglawalthn+tglawalbln+tglawaltgl;
							arrtgl = tglakhir.split("/");
							var tglakhirthn = arrtgl[2];
							var tglakhirbln = arrtgl[1];
							if(tglakhirbln.length < 2)
								tglakhirbln = '0'+arrtgl[1];
							var tglakhirtgl = arrtgl[0];
							if(tglakhirtgl.length < 2)
								tglakhirtgl = '0'+arrtgl[0];
							var arrtglakhir = tglakhirthn+tglakhirbln+tglakhirtgl;
							
							var dtpTglAwal = new Date(tglawal);
							var dtpTglAkhir = new Date(tglakhir);
							if(dtpTglAwal > dtpTglAkhir)
							{
								alert("Tanggal awal tidak boleh lebih besar dari tanggal akhir.");
								jQuery("#tgl_awal").focus();
							}
							else
							{
								if(jenis == 0)
								{
									loadSuratMasuk(idxtgl,arrtglawal,arrtglakhir);
								}
								else
								{
									loadSuratKeluar(idxtgl,arrtglawal,arrtglakhir);
								}
								jQuery(this).dialog("close"); 
								jQuery('#frmdisposisi').remove();
								jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
								jQuery("#list").trigger("reloadGrid");
							}
						} },
						});
					});
				}
			}
			
			function loadBln(jenis)
			{
				idxbln = jQuery("#pilihbln").val();
				if(idxbln < "1")
				{
					if(jenis == 0)
					{
						loadSuratMasukBln(idxbln,"");
					}
					else
					{
						loadSuratKeluarBln(idxbln,"");
					}
				}
				else
				{
					$(function() {
						jQuery('#frmdisposisi').load('frmbln.php?sessid='+<?php echo $_SESSION["UserID"];?>);
						jQuery('#frmdisposisi').dialog({width:230,modal:true,title:'Pilih Bulan',
						buttons: { "Batal": function() {
							jQuery(this).dialog("close"); 
							jQuery('#frmdisposisi').remove();
							jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						}, "Cari": function() { 
							var bln = jQuery("#bln").val();
							var arrbln = bln.split(" ");
							if(arrbln.length != 2)
							{
								alert("Format Bulan Salah!!");
							}
							else
							{
								var angkabln = namaBlnToAngka(arrbln[0]);
								var thn = arrbln[1];
								var blncari = thn+'-'+angkabln;
								if(jenis == 0)
								{
									loadSuratMasukBln(idxbln,blncari);
								}
								else
								{
									loadSuratKeluarBln(idxbln,blncari);
								}
								jQuery(this).dialog("close"); 
								jQuery('#frmdisposisi').remove();
								jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
								jQuery("#list").trigger("reloadGrid");
							}
						} },
						});
					});
				}
			}
			
			function loadIdx(jenis)
			{
				idxbln = jQuery("#pilihbln").val();
				if(idxbln < "1")
				{
					getIndex(jenis,"");
				}
				else
				{
					$(function() {
						jQuery('#frmdisposisi').load('frmbln.php?sessid='+<?php echo $_SESSION["UserID"];?>);
						jQuery('#frmdisposisi').dialog({width:230,modal:true,title:'Pilih Bulan',
						buttons: { "Batal": function() {
							jQuery(this).dialog("close"); 
							jQuery('#frmdisposisi').remove();
							jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						}, "Cari": function() { 
							var bln = jQuery("#bln").val();
							var arrbln = bln.split(" ");
							if(arrbln.length != 2)
							{
								alert("Format Bulan Salah!!");
							}
							else
							{
								var angkabln = namaBlnToAngka(arrbln[0]);
								var thn = arrbln[1];
								var blncari = thn+'-'+angkabln;
								getIndex(jenis,blncari);
								jQuery(this).dialog("close"); 
								jQuery('#frmdisposisi').remove();
								jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
								//jQuery("#list").trigger("reloadGrid");
							}
						} },
						});
					});
				}
			}
			
			function loadDataIdx(jenis)
			{
				idxbln = jQuery("#pilihbln").val();
				idxidx = jQuery("#pilihidx").val();
				if(idxbln < "1")
				{
					if(jenis == 0)
					{
						loadSuratMasukIndex(idxbln,"",idxidx);
					}
					else
					{
						loadSuratKeluarIndex(idxbln,"",idxidx);
					}
				}
				else
				{
					bln = jQuery("#periode").val();
					if(jenis == 0)
					{
						loadSuratMasukIndex(idxbln,bln,idxidx);
					}
					else
					{
						loadSuratKeluarIndex(idxbln,bln,idxidx);
					}
					//jQuery("#periode").val("");
				}
			}
			
			function getIndex(jenis,bln)
			{
				jQuery("#periode").val(bln);
				var idxm = jQuery.ajax({
					url: 'idx.php?jenis='+jenis+'&bln='+bln+'&sessid='+<?php echo $_SESSION["UserID"];?>, 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Surat Masuk/Keluar Belum Ada.');
					}
				}).responseText;
				
				jQuery("#pilihidx").empty();
				var idxm_ = idxm.split(";");
				jQuery.each(idxm_,function(i,val){
					var obj = val.split(":");
					jQuery("#pilihidx").append("<option value='"+obj[0]+"'>"+obj[1]+"</option>");
				});
			}
			
			function backupDB()
			{
				jQuery('#frmdisposisi').load('backupdb.php?sessid='+<?php echo $_SESSION["UserID"];?>);
				jQuery('#frmdisposisi').dialog({width:420,height:225,modal:true,title:'Backup Database',
				buttons: { "Batal": function() {
					jQuery(this).dialog("close"); 
					jQuery('#frmdisposisi').remove();
					jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
				}, "Backup": function() { 
					var namafolder = jQuery("#namafolder").val();
					if(namafolder == "")
					{
						alert("Nama Folder Backup Belum Di Isi.");
						jQuery("#namafolder").focus();
					}
					else
					{
						dataString = jQuery("#frmsubmit").serialize();
						jQuery.ajax({
							url: 'backupdb.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							type: "POST",
							data: dataString,
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (data == "error") 
									alert('Backup Database Tidak Berhasil\nKemungkinan Folder Backup Tidak Benar!');
								else
									alert('Backup Database Berhasil!');
							}
						});
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						jQuery("#list").trigger("reloadGrid");
					}
				} },
				})
			}
			
			function restoreDB()
			{
				jQuery('#frmdisposisi').load('restoredb.php?sessid='+<?php echo $_SESSION["UserID"];?>);
				jQuery('#frmdisposisi').dialog({width:420,height:210,modal:true,title:'Restore Database',
				buttons: { "Tutup": function() {
					jQuery(this).dialog("close"); 
					jQuery('#frmdisposisi').remove();
					jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
				}, "Restore": function() { 
					var namafile = jQuery("#namafile").val();
					if(namafile == "")
					{
						alert("Nama File Backup Belum Di Isi.");
						jQuery("#namafile").focus();
					}
					else
					{
						jQuery("#filebackup").val(namafile);
						dataString = jQuery("#frmsubmit").serialize();
						jQuery.ajax({
							url: 'restoredb.php?sessid='+<?php echo $_SESSION["UserID"];?>, 
							type: "POST",
							data: dataString,
							datatype: "json",
							async: false, 
							success: function(data, result) {
								if (data == "error") 
									alert('Restore Database Tidak Berhasil\nKemungkinan File Backup Tidak Benar!');
								else
									alert('Restore Database Berhasil!');
							}
						});
						jQuery(this).dialog("close"); 
						jQuery('#frmdisposisi').remove();
						jQuery('#frm').prepend("<div id='frmdisposisi'></div>");
						jQuery("#list").trigger("reloadGrid");
					}
				}},
				})
			}

			function namaBlnToAngka(namabln)
			{
				var angka = "";
				switch(namabln)
				{
					case "January":
						angka = "01";
						break;
					case "February":
						angka = "02";
						break;
					case "March":
						angka = "03";
						break;
					case "April":
						angka = "04";
						break;
					case "May":
						angka = "05";
						break;
					case "June":
						angka = "06";
						break;
					case "July":
						angka = "07";
						break;
					case "August":
						angka = "08";
						break;
					case "September":
						angka = "09";
						break;
					case "October":
						angka = "10";
						break;
					case "November":
						angka = "11";
						break;
					case "December":
						angka = "12";
						break;
				}
				return angka;
			}
		</script>
		<style type="text/css">
			/* set height grid header -- jgn pake, chrome menjadi berat sekali*/
			.ui-jqgrid-htable th {
				height:auto;
				overflow:hidden;
				padding-right:4px;
				padding-top:2px;
				position:relative;
				vertical-align:text-middle;
				white-space:normal !important;
			}
		</style>
	</head>
	<body>
		<div id="header"></div>
		<div id="menu" class="jquerycssmenu"><?php include("menu.php");?></div>
		<div id="content" style="height:350px">
			<div id="divtgl"></div>
			<form id="frm" method="post" action="csvExport.php">
				<div id="frmdisposisi"></div>
				<table id="list"></table>
				<div id="page"></div>
				<input id="csvBuffer" name="csvBuffer" type="hidden" value="">
				<input id="typeinfo" name="typeinfo" type="hidden" value="">
			</form>
			<input id="userlvl" type="hidden" value="">
			<input id="adddata" type="hidden" value="false">
			<input id="editdata" type="hidden" value="false">
			<input id="deldata" type="hidden" value="false">
			<input id="periode" type="hidden" value="">
		</div>
		<div id="footer">
			Copyright @2011 STAIN Prof. Dr. H. Mahmud Yunus Batusangkar<br>
			Jl. Sudirman No. 137 Kuburajo, Lima Kaum<br>
			Batusangkar
		</div>
	</body>
</html>