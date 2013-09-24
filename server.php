<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	$myfunction = new MyFunction();
	$responce = new stdClass();
	
	$mode = isset($_REQUEST["mode"])?$_REQUEST["mode"]:"";
	$type = isset($_REQUEST["type"])?$_REQUEST["type"]:"";
	$oper = isset($_REQUEST["oper"])?$_REQUEST["oper"]:"";

	$page = isset($_REQUEST['page'])?$_REQUEST["page"]:0; // get the requested page
	$limit = isset($_REQUEST['rows'])?$_REQUEST["rows"]:0; // get how many rows we want to have into the grid
	$sidx = isset($_REQUEST['sidx'])?$_REQUEST["sidx"]:1; // get index row - i.e. user click to sort
	$sord = isset($_REQUEST['sord'])?$_REQUEST["sord"]:"ASC"; // get the direction
	if(!$sidx) $sidx =1;
	/* BEGIN SEARCH OPTION */
	$where = "";
	$where2 = "";
	$wherepemilik = "";
	$whereoperator = "";
	$whereizin = "";
	$wherenagari = "";
	
	$searchOn = strip_tags(isset($_REQUEST['_search'])?$_REQUEST["_search"]:"");
	if($searchOn == 'true') 
	{
		/*if($type == 'kode') 
		{
			$fld = strip_tags($_REQUEST['searchField']);
			$fldata = mysql_real_escape_string(strip_tags($_REQUEST['searchString']));
			$foper = strip_tags($_REQUEST['searchOper']);
			
			// costruct where
			switch ($foper) {
				case "cn":
					$where .= " WHERE ".$fld." LIKE '%".$fldata."%'";
					break;
				case "eq":
					$where .= " WHERE ".$fld." = ".$fldata;
					break;
				default:
					$where = "";
			}
			
		}
		else*/ if($type == 'disposisi' || $type == 'suratkeluar' || $type == 'kode' || $type == 'ttd')
		{
			$filters = "";
			if(get_magic_quotes_gpc())
			{
				$filters = stripslashes($_REQUEST['filters']);
			}
			else
			{
				$filters = $_REQUEST['filters'];
			}
			$filters = json_decode($filters,true);
			$andor = $filters['groupOp'];

			for($i=0;$i<count($filters['rules']);$i++)
			{
				$fld = $filters['rules'][$i]['field'];
				$data = mysql_real_escape_string($filters['rules'][$i]['data']);
				$foper = $filters['rules'][$i]['op'];
				
				$sqloper_bef = "";
				$sqloper_aft = "";
				
				if($type == "disposisi")
					$tgl = array('s.tgl_terima');
				if($type == "suratkeluar")
					$tgl = array('tgl_surat');
				if($type == "kode")
				{
					if($fld == "b.idbagian" && $data == "0")
						break;
				}
				switch($foper)
				{
					case "cn":
						$sqloper_bef = "LIKE '%";
						$sqloper_aft = "%'";
						break;
					case "eq":
						if($type == "disposisi" || $type == "suratkeluar")
						{
							if(in_array($fld,$tgl))
							{
								$data = $myfunction->dateformatSlashDb($data);
							}
						}
						$sqloper_bef = "= '";
						$sqloper_aft = "'";
						break;
					case "le":
						$data = $myfunction->dateformatSlashDb($data);
						$sqloper_bef = "<= '";
						$sqloper_aft = "'";
						break;
					case "ge":
						$data = $myfunction->dateformatSlashDb($data);
						$sqloper_bef = ">= '";
						$sqloper_aft = "'";
						break;
				}
				if($data != "")
				{
					if($where == "")
					{
						$where = " WHERE ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						$where2 = " HAVING ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						/*if($type == 'bts_all' || $type == 'detail_operator' || $type == 'detail_kecamatan')
						{
							$_field = explode(".",$fld);
							if($_field[0]=="t" || $_field[0]=="i")
								$whereizin = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="p")
								$wherepemilik = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="n")
								$wherenagari = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="o")
								$whereoperator = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}
						if($type == 'nagari')
						{
							$where = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}*/
					}
					else
					{
						$where .= " ".$andor." ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						$where2 .= " ".$andor." ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						/*if($type == 'bts_all' || $type == 'detail_operator' || $type == 'detail_kecamatan')
						{
							$_field = explode(".",$fld);
							if($_field[0]=="t" || $_field[0]=="i")
								$whereizin .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="p")
								$wherepemilik .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="n")
								$wherenagari .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="o")
								$whereoperator .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}*/
					}	
				}				
			}
			
			// costruct where
			
		}
	}
	/* END SEARCH OPTION */
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());

	mysql_select_db($database) or die("Error conecting to db.");

	if($oper == "")
	{
		if($type == "disposisi")
		{
			if($searchOn == 'false') 
			{
				$where = " WHERE s.isaktif = 1";
				$where2 = " HAVING s.isaktif = 1";
				$idxtgl = isset($_REQUEST["idxtgl"])?$_REQUEST["idxtgl"]:"0";
				if($idxtgl == "0")
				{
					$tgl = date("Y-m-d");
					$where .= " AND s.tgl_terima = '".$tgl."'";
					$where2 .= " AND s.tgl_terima = '".$tgl."'";
				}
				else if($idxtgl == "1")
				{
					$tgl = date("Y-m-d");
					$where .= " AND SUBSTR(s.tgl_terima,1,7) = '".substr($tgl,0,7)."'";
					$where2 .= " AND SUBSTR(s.tgl_terima,1,7) = '".substr($tgl,0,7)."'";
				}
				else if($idxtgl == "2")
				{
					$tglawal = isset($_REQUEST["tglawal"])?$myfunction->dateformatShortToDb($_REQUEST["tglawal"]):date("Y-m-d");
					$tglakhir = isset($_REQUEST["tglakhir"])?$myfunction->dateformatShortToDb($_REQUEST["tglakhir"]):date("Y-m-d");
					$where .= " AND (s.tgl_terima BETWEEN '".$tglawal."' AND '".$tglakhir."')";
					$where2 .= " AND (s.tgl_terima BETWEEN '".$tglawal."' AND '".$tglakhir."')";
				}
			}
			//$sql = "SELECT COUNT(s.idsurat) AS count FROM suratmasuk s ".$where;
			$sql = "SELECT COUNT(a.idsurat) AS count FROM (
				SELECT s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat,
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tgl_disposisi1,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tujuan_disposisi1, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tgl_disposisi2,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tujuan_disposisi2, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tgl_disposisi3,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tujuan_disposisi3, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tgl_disposisi4,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tujuan_disposisi4, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tgl_disposisi5,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tujuan_disposisi5, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tgl_disposisi6,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tujuan_disposisi6 FROM suratmasuk s 
				LEFT JOIN disposisi d ON s.idsurat=d.idsurat".$where." 
				GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat) a";
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat,
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tgl_disposisi1,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tujuan_disposisi1, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tgl_disposisi2,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tujuan_disposisi2, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tgl_disposisi3,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tujuan_disposisi3, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tgl_disposisi4,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tujuan_disposisi4, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tgl_disposisi5,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tujuan_disposisi5, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tgl_disposisi6,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tujuan_disposisi6 
				FROM suratmasuk s
				LEFT JOIN disposisi d ON s.idsurat=d.idsurat".$where." 
				GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idsurat'];
				$responce->rows[$i]['cell']=array($row['idsurat'],$j+1,$row['tgl_terima'],$row['no_surat'],$row['no_disposisi'],$row['pengirim'],$row['perihal'],$myfunction->dateformatSlashView($row['tgl_disposisi1'])."<br>".$row['tujuan_disposisi1'],$myfunction->dateformatSlashView($row['tgl_disposisi2'])."<br>".$row['tujuan_disposisi2'],$myfunction->dateformatSlashView($row['tgl_disposisi3'])."<br>".$row['tujuan_disposisi3'],$myfunction->dateformatSlashView($row['tgl_disposisi4'])."<br>".$row['tujuan_disposisi4'],$myfunction->dateformatSlashView($row['tgl_disposisi5'])."<br>".$row['tujuan_disposisi5'],$myfunction->dateformatSlashView($row['tgl_disposisi6'])."<br>".$row['tujuan_disposisi6']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "suratkeluar")
		{
			if($searchOn == 'false') 
			{
				$where = " WHERE isaktif = 1";
				$idxtgl = isset($_REQUEST["idxtgl"])?$_REQUEST["idxtgl"]:"0";
				if($idxtgl == "0")
				{
					$tgl = date("Y-m-d");
					$where .= " AND tgl_surat = '".$tgl."'";
				}
				else if($idxtgl == "1")
				{
					$tgl = date("Y-m-d");
					$where .= " AND substr(tgl_surat,1,7) = '".substr($tgl,0,7)."'";
				}
				else if($idxtgl == "2")
				{
					$tglawal = isset($_REQUEST["tglawal"])?$_REQUEST["tglawal"]:date("Y-m-d");
					$tglakhir = isset($_REQUEST["tglakhir"])?$_REQUEST["tglakhir"]:date("Y-m-d");
					$where .= " AND (tgl_surat BETWEEN '".$tglawal."' AND '".$tglakhir."')";
				}
			}
			$sql = "SELECT COUNT(idsuratkeluar) AS count FROM suratkeluar ".$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT idsuratkeluar,tgl_surat,no_surat,pengirim,perihal,tujuan,penandatangan,penyimpanan,
				arsip,file_loc,status_surat,kondisi_surat,lampiran  
				FROM suratkeluar ".
				$where." order by tgl_surat,no_surat4 LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$arsip = "Tidak Ada";
				if($row['arsip']=='1')
				{
					$arsip = "Ada";
				}

				$responce->rows[$i]['id']=$row['idsuratkeluar'];
				$responce->rows[$i]['cell']=array($row['idsuratkeluar'],$j+1,$row['tgl_surat'],$row['no_surat'],$row['pengirim'],$row['perihal'],$row['tujuan'],$row['penandatangan'],$row['penyimpanan'],$arsip,$row['status_surat'],$row['kondisi_surat'],$row['lampiran'],$row['file_loc']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "suratmasukbln")
		{
			$where = " WHERE s.isaktif = 1";
			$where2 = " HAVING s.isaktif = 1";
			$bln = isset($_REQUEST["bln"])?$_REQUEST["bln"]:date('Y-m');
			$where .= " AND SUBSTR(s.tgl_terima,1,7) = '".$bln."'";
			$where2 .= " AND SUBSTR(s.tgl_terima,1,7) = '".$bln."'";
			
			$sql = "SELECT COUNT(s.idsurat) AS count FROM suratmasuk s ".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT s.idsurat,CONCAT(b.bagian,' (',k.kode,')') AS indek,
				s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat,
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tgl_disposisi1,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tujuan_disposisi1, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tgl_disposisi2,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tujuan_disposisi2, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tgl_disposisi3,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tujuan_disposisi3, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tgl_disposisi4,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tujuan_disposisi4, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tgl_disposisi5,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tujuan_disposisi5, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tgl_disposisi6,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tujuan_disposisi6 
				FROM suratmasuk s
				INNER JOIN kodesurat k ON s.idkode=k.idkode 
				INNER JOIN bagian b ON k.idbagian=b.idbagian 
				LEFT JOIN disposisi d ON s.idsurat=d.idsurat
				GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat ".
				$where2." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idsurat'];
				$responce->rows[$i]['cell']=array($row['idsurat'],$j+1,$row['indek'],$row['tgl_terima'],$row['no_surat'],$row['no_disposisi'],$row['pengirim'],$row['perihal'],$myfunction->dateformatSlashView($row['tgl_disposisi1'])."<br>".$row['tujuan_disposisi1'],$myfunction->dateformatSlashView($row['tgl_disposisi2'])."<br>".$row['tujuan_disposisi2'],$myfunction->dateformatSlashView($row['tgl_disposisi3'])."<br>".$row['tujuan_disposisi3'],$myfunction->dateformatSlashView($row['tgl_disposisi4'])."<br>".$row['tujuan_disposisi4'],$myfunction->dateformatSlashView($row['tgl_disposisi5'])."<br>".$row['tujuan_disposisi5'],$myfunction->dateformatSlashView($row['tgl_disposisi6'])."<br>".$row['tujuan_disposisi6']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "suratkeluarbln")
		{
			$where = " WHERE s.isaktif = 1";
			$bln = isset($_REQUEST["bln"])?$_REQUEST["bln"]:date('Y-m');
			$where .= " AND SUBSTR(s.tgl_surat,1,7) = '".$bln."'";
			$sql = "SELECT COUNT(s.idsuratkeluar) AS count FROM suratkeluar s ".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT s.idsuratkeluar,CONCAT(b.bagian,' (',k.kode,')') AS indek,s.tgl_surat,
				s.no_surat,s.pengirim,s.perihal,s.tujuan,s.arsip,s.file_loc,s.status_surat,
				s.kondisi_surat,s.lampiran FROM suratkeluar s 
				INNER JOIN kodesurat k ON s.idkode=k.idkode 
				INNER JOIN bagian b ON k.idbagian=b.idbagian ".
				$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$arsip = "Tidak Ada";
				if($row['arsip']=='1')
				{
					$arsip = "Ada";
				}

				$responce->rows[$i]['id']=$row['idsuratkeluar'];
				$responce->rows[$i]['cell']=array($row['idsuratkeluar'],$j+1,$row['indek'],$row['tgl_surat'],$row['no_surat'],$row['pengirim'],$row['perihal'],$row['tujuan'],$arsip,$row['status_surat'],$row['kondisi_surat'],$row['lampiran'],$row['file_loc']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "suratmasukidx")
		{
			$where = " WHERE s.isaktif = 1";
			$where2 = " HAVING s.isaktif = 1";
			$bln = isset($_REQUEST["bln"])?$_REQUEST["bln"]:date('Y-m');
			$idx = isset($_REQUEST["idx"])?$_REQUEST["idx"]:"0";
			$where .= " AND SUBSTR(s.tgl_terima,1,7) = '".$bln."'";
			$where2 .= " AND SUBSTR(s.tgl_terima,1,7) = '".$bln."'";
			if($idx != "0")
			{
				$where .= " AND b.idbagian = ".$idx;
				$where2 .= " AND b.idbagian = ".$idx;
			}			
			
			$sql = "SELECT COUNT(b.idbagian) AS count FROM suratmasuk s
				INNER JOIN kodesurat k ON s.idkode=k.idkode
				INNER JOIN bagian b ON k.idbagian=b.idbagian ".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT s.idsurat,CONCAT(b.bagian,' (',k.kode,')') AS indek,
				s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat,
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tgl_disposisi1,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tujuan_disposisi1, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tgl_disposisi2,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tujuan_disposisi2, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tgl_disposisi3,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tujuan_disposisi3, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tgl_disposisi4,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tujuan_disposisi4, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tgl_disposisi5,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tujuan_disposisi5, 
				IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tgl_disposisi6,
				IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tujuan_disposisi6 
				FROM suratmasuk s
				INNER JOIN kodesurat k ON s.idkode=k.idkode 
				INNER JOIN bagian b ON k.idbagian=b.idbagian 
				LEFT JOIN disposisi d ON s.idsurat=d.idsurat
				GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.isaktif,s.no_surat ".
				$where2." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idsurat'];
				$responce->rows[$i]['cell']=array($row['idsurat'],$j+1,$row['indek'],$row['tgl_terima'],$row['no_surat'],$row['no_disposisi'],$row['pengirim'],$row['perihal'],$myfunction->dateformatSlashView($row['tgl_disposisi1'])."<br>".$row['tujuan_disposisi1'],$myfunction->dateformatSlashView($row['tgl_disposisi2'])."<br>".$row['tujuan_disposisi2'],$myfunction->dateformatSlashView($row['tgl_disposisi3'])."<br>".$row['tujuan_disposisi3'],$myfunction->dateformatSlashView($row['tgl_disposisi4'])."<br>".$row['tujuan_disposisi4'],$myfunction->dateformatSlashView($row['tgl_disposisi5'])."<br>".$row['tujuan_disposisi5'],$myfunction->dateformatSlashView($row['tgl_disposisi6'])."<br>".$row['tujuan_disposisi6']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "suratkeluaridx")
		{
			$where = " WHERE s.isaktif = 1";
			$bln = isset($_REQUEST["bln"])?$_REQUEST["bln"]:date('Y-m');
			if($bln == "")
				$bln = date('Y-m');
			$idx = isset($_REQUEST["idx"])?$_REQUEST["idx"]:"0";
			$where .= " AND SUBSTR(s.tgl_surat,1,7) = '".$bln."'";
			if($idx != "0")
			{
				$where .= " AND b.idbagian = ".$idx;
			}
			$sql = "SELECT COUNT(b.idbagian) AS count FROM suratkeluar s
				INNER JOIN kodesurat k ON s.idkode=k.idkode
				INNER JOIN bagian b ON k.idbagian=b.idbagian ".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			
			$SQL = "SELECT s.idsuratkeluar,CONCAT(b.bagian,' (',k.kode,')') AS indek,s.tgl_surat,
				s.no_surat,s.pengirim,s.perihal,s.tujuan,s.arsip,s.file_loc,s.status_surat,
				s.kondisi_surat,s.lampiran FROM suratkeluar s 
				INNER JOIN kodesurat k ON s.idkode=k.idkode 
				INNER JOIN bagian b ON k.idbagian=b.idbagian ".
				$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$arsip = "Tidak Ada";
				if($row['arsip']=='1')
				{
					$arsip = "Ada";
				}

				$responce->rows[$i]['id']=$row['idsuratkeluar'];
				$responce->rows[$i]['cell']=array($row['idsuratkeluar'],$j+1,$row['indek'],$row['tgl_surat'],$row['no_surat'],$row['pengirim'],$row['perihal'],$row['tujuan'],$arsip,$row['status_surat'],$row['kondisi_surat'],$row['lampiran'],$row['file_loc']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "kode")
		{
			$sql = "SELECT COUNT(k.idkode) AS count FROM bagian b 
				   INNER JOIN kodesurat k ON b.idbagian=k.idbagian".$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "SELECT k.idkode,b.bagian,k.hal,k.kode FROM bagian b 
				   INNER JOIN kodesurat k ON b.idbagian=k.idbagian".
				   $where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idkode'];
				$responce->rows[$i]['cell']=array($row['idkode'],$j+1,$row['bagian'],$row['hal'],$row['kode']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "bagian")
		{
			$sql = "SELECT COUNT(idbagian) AS count FROM bagian".$where;

			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "SELECT idbagian,bagian FROM bagian".
				   $where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idbagian'];
				$responce->rows[$i]['cell']=array($row['idbagian'],$j+1,$row['bagian']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "ttd")
		{
			$sql = "SELECT COUNT(idttd) AS count FROM ttd".$where;

			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "SELECT idttd,idxttd,namattd,jabatan FROM ttd".
				   $where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['idttd'];
				$responce->rows[$i]['cell']=array($row['idttd'],$j+1,$row['idxttd'],$row['namattd'],$row['jabatan']);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "user")
		{
			$sql = "SELECT COUNT(iduser) AS count FROM user".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select a.iduser,a.nama_lengkap,a.user,b.userlevel,IF(a.status>0,'Aktif','Tidak Aktif') AS status FROM user a inner join userlevel b on a.iduserlevel=b.iduserlevel order by a.iduser LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0;
			$j=$start;			
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['iduser'];
				$responce->rows[$i]['cell']=array($row['iduser'],$j+1,'',$row['nama_lengkap'],$row['user'],'','',$row['userlevel'],$row['status']);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
		else if($type == "userlevel")
		{
			$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select iduserlevel,userlevel FROM userlevel LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['iduserlevel'];
				$responce->rows[$i]['cell']=array($row['iduserlevel'],$j+1,$row['userlevel']);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
		else if($type == "useraccess")
		{
			$sql = "SELECT COUNT(iduseraccess) AS count FROM useraccess".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if($count==0)	//useraccess is not exist, create a new one
			{
				$sqluseraccess = "INSERT INTO useraccess (iduseraccess,iduserlevel,adddata,editdata,deldata) VALUES (1,1,1,1,1)";
				if (!mysql_query($sqluseraccess, $db)) {
					echo mysql_errno($db) . "1: " . mysql_error($db) . "\n";
				}
			}
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select a.iduseraccess,a.iduserlevel,b.userlevel,IF(a.adddata>0,'Bisa','Tidak Bisa') AS adddata,IF(a.editdata>0,'Bisa','Tidak Bisa') AS editdata,IF(a.deldata>0,'Bisa','Tidak Bisa') AS deldata FROM useraccess a inner join userlevel b on a.iduserlevel=b.iduserlevel order by a.iduseraccess LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row['iduseraccess'];
				$responce->rows[$i]['cell']=array($row['iduseraccess'],$row['iduserlevel'],$j+1,$row['userlevel'],$row['adddata'],$row['editdata'],$row['deldata']);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
	}
	else if($oper == "edit")
	{
		if($type == "bagian")
		{
			$bagian = mysql_real_escape_string($_REQUEST['bagian']);
			$idbagian = $_REQUEST['id'];
			$sql = "SELECT COUNT(idbagian) AS count FROM bagian WHERE idbagian != ".$idbagian." AND bagian = '".$bagian."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE bagian SET bagian = '".$bagian."' where idbagian = ".$idbagian;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Bagian '".$bagian."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "kode")
		{
			$idkode = $_REQUEST['id'];
			$hal = mysql_real_escape_string($_REQUEST['hal']);
			$kode = mysql_real_escape_string($_REQUEST['kode']);
			$sql = "SELECT COUNT(idkode) AS count FROM kodesurat WHERE hal = '".$hal."' AND kode = '".$kode."' AND idkode != ".$idkode;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE kodesurat SET hal = '".$hal."', kode = '".$kode."' where idkode = ".$idkode;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Data Kode '".$kode."' Sudah Ada di Database";
			}
		}
		else if($type == "ttd")
		{
			$idttd = $_REQUEST['id'];
			$idxttd = mysql_real_escape_string($_REQUEST['idxttd']);
			$namattd = mysql_real_escape_string($_REQUEST['namattd']);
			$jabatan = mysql_real_escape_string($_REQUEST['jabatan']);
			$sql = "SELECT COUNT(idttd) AS count FROM ttd WHERE idxttd = '".$idxttd."' AND namattd = '".$namattd."' AND jabatan = '".$jabatan."' AND idttd != ".$idttd;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE ttd SET idxttd = '".$idxttd."', namattd = '".$namattd."', jabatan = '".$jabatan."' where idttd = ".$idttd;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Data Indek Penandatangan Surat '".$idxttd."' Sudah Ada di Database";
			}
		}
		else if($type == "user")
		{
			$iduser = mysql_real_escape_string($_REQUEST['id']);
			$nama_lengkap = mysql_real_escape_string($_REQUEST['nama_lengkap']);
			$user = mysql_real_escape_string($_REQUEST['user']);
			$password = mysql_real_escape_string($_REQUEST['password']);
			$password2 = mysql_real_escape_string($_REQUEST['password2']);
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$status = mysql_real_escape_string($_REQUEST['status']);
			$changepass = mysql_real_escape_string($_REQUEST['changepass']);

			$err_admin = "";
			if($iduser==1)	//Administrator
			{
				$sql = "SELECT nama_lengkap,user,iduserlevel,status FROM user WHERE iduser = ".$iduser;
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$n_lengkap = $row['nama_lengkap'];
				$n_user = $row['user'];
				$n_level = $row['iduserlevel'];
				$n_status = $row['status'];
				if($n_user != $user)
					$err_admin .= "Pengguna 'admin' Tidak Bisa di Ubah.";
				if($n_level != $userlevel)
					$err_admin .= " Tingkatan Administrator Tidak Bisa di Ubah.";
				if($n_status != $status)
					$err_admin .= " Status Administrator Tidak Bisa di Non Aktifkan.";
			}

			if($err_admin != "")
				echo $err_admin;
			else
			{
				if($changepass=="on")
				{
					if($password=="" || $password2=="")
						echo "Password Lama dan Password Baru Belum Di Isi";
					else
					{
						$sql = "SELECT COUNT(iduser) AS count FROM user WHERE iduser = ".$iduser." and password='".md5($password)."'";
						$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
						$count = $row['count'];
						if($count == 0)
							echo "Password Lama Tidak Benar";
						else
						{
							$SQL = "UPDATE user SET nama_lengkap='".$nama_lengkap."',user = '".$user."',password='".md5($password2)."',iduserlevel=".$userlevel.",status=".$status." WHERE iduser = ".$iduser;
							//echo $SQL;
							if (!mysql_query($SQL, $db)) {
								echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
							}
						}
					}
				}
				else
				{
					$SQL = "UPDATE user SET nama_lengkap='".$nama_lengkap."',user = '".$user."',iduserlevel=".$userlevel.",status=".$status." WHERE iduser = ".$iduser;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "userlevel")
		{
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$iduserlevel = mysql_real_escape_string($_REQUEST['id']);
			if($iduserlevel==1)	//Administrator
			{
				echo "Tingkatan Ini Tidak Bisa di Edit";
			}
			else
			{
				$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel WHERE iduserlevel != ".$iduserlevel." AND userlevel = '".$userlevel."'";
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$count = $row['count'];
				if($count == 0)
				{
					$SQL = "UPDATE userlevel SET userlevel = '".$userlevel."' WHERE iduserlevel = ".$iduserlevel;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
				else
				{
					echo "Nama Tingkatan '".$userlevel."' Sudah Ada di Database";
				}
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "useraccess")
		{
			$iduseraccess = mysql_real_escape_string($_REQUEST['id']);
			$iduserlevel = mysql_real_escape_string($_REQUEST['iduserlevel']);
			$adddata = mysql_real_escape_string($_REQUEST['adddata']);
			$editdata = mysql_real_escape_string($_REQUEST['editdata']);
			$deldata = mysql_real_escape_string($_REQUEST['deldata']);

			$SQL = "UPDATE useraccess SET adddata = ".$adddata.",editdata = ".$editdata.",deldata = ".$deldata." WHERE iduseraccess = ".$iduseraccess;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
	}
	else if($oper == "add")
	{
		if($type == "bagian")
		{
			$bagian = mysql_real_escape_string($_REQUEST['bagian']);
			$sql = "SELECT COUNT(idbagian) AS count FROM bagian WHERE bagian = '".$bagian."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO bagian (bagian) VALUES ('".$bagian."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Bagian '".$bagian."' Sudah Ada di Database";
			}
		}
		else if($type == "kode")
		{
			$idbagian = $_REQUEST['bagian'];
			$hal = mysql_real_escape_string($_REQUEST['hal']);
			$kode = mysql_real_escape_string($_REQUEST['kode']);
			$sql = "SELECT COUNT(idkode) AS count FROM kodesurat WHERE hal = '".$hal."' AND kode = '".$kode."' AND idbagian = ".$idbagian;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO kodesurat (hal,kode,idbagian) VALUES ('".$hal."','".$kode."',".$idbagian.")";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Kode '".$kode."' Sudah Ada di Database";
			}
		}
		else if($type == "ttd")
		{
			$idxttd = mysql_real_escape_string($_REQUEST['idxttd']);
			$namattd = mysql_real_escape_string($_REQUEST['namattd']);
			$jabatan = mysql_real_escape_string($_REQUEST['jabatan']);
			$sql = "SELECT COUNT(idttd) AS count FROM ttd WHERE idxttd = '".$idxttd."' AND namattd = '".$namattd."' AND jabatan = '".$jabatan."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO ttd (idxttd,namattd,jabatan) VALUES ('".$idxttd."','".$namattd."','".$jabatan."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Indek Penandatangan Surat '".$idxttd."' Sudah Ada di Database";
			}
		}
		else if($type == "user")
		{
			$nama_lengkap = mysql_real_escape_string($_REQUEST['nama_lengkap']);
			$user = mysql_real_escape_string($_REQUEST['user']);
			$password = mysql_real_escape_string($_REQUEST['password']);
			$password2 = mysql_real_escape_string($_REQUEST['password2']);
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$status = mysql_real_escape_string($_REQUEST['status']);

			$err_user = "";
			if($nama_lengkap == "")
				$err_user = "Nama Pengguna Belum di Isi";
			if($user == "")
				$err_user = "Pengguna Belum di Isi";
			if($password == "")
				$err_user = "Password Belum di Isi";
			if($password2 == "")
				$err_user = "Password (Lagi) Belum di Isi";
				
			if($err_user != "")
			{
				echo $err_user;
			}
			else
			{
				if($password != $password2)
				{
					echo "Password Tidak Sama";
				}
				else
				{
					$sql = "SELECT COUNT(iduser) AS count FROM user WHERE user = '".$user."'";
					$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					$count = $row['count'];
					if($count > 0)
					{
						echo "Pengguna '".$user."' Sudah Ada di Database";
					}
					else
					{
						$SQL = "INSERT INTO user (nama_lengkap,user,password,iduserlevel,status) VALUES ('".$nama_lengkap."','".$user."','".md5($password2)."',".$userlevel.",".$status.")";
						if (!mysql_query($SQL, $db)) {
							echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
						}
					}
				}
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "userlevel")
		{
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel WHERE userlevel = '".$userlevel."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO userlevel (userlevel) VALUES ('".$userlevel."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				$iduserlevel = mysql_insert_id();
				//add user access
				$SQL = "INSERT INTO useraccess (iduserlevel,adddata,editdata,deldata) VALUES (".$iduserlevel.",0,0,0)";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Tingkatan '".$userlevel."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
	}
	else if($oper == "del")
	{
		if($type == "bagian")
		{
			$idbagian = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(k.idbagian) AS count FROM kodesurat k inner join bagian b on k.idbagian=b.idbagian where k.idbagian=".$idbagian;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM bagian WHERE idbagian = ".$idbagian;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Data Bagian Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Kode Surat";
			}
		}
		else if($type == "kode")
		{
			$idkode = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(s.idkode) AS count FROM surat s inner join kodesurat k on s.idkode=k.idkode where s.idkode=".$idkode;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM kodesurat WHERE idkode = ".$idkode;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Kode Surat Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Disposisi Surat";
			}
		}
		else if($type == "ttd")
		{
			$idttd = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT idxttd FROM ttd WHERE idttd=".$idttd;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$idxttd = $row['idxttd'];

			$sql = "SELECT COUNT(idsuratkeluar) AS count FROM suratkeluar WHERE no_surat2 = '".$idxttd."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM ttd WHERE idttd = ".$idttd;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Data Indek Penandatangan Surat Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Surat Keluar";
			}
		}
		else if($type == "disposisi")
		{
			$idsurat = $_REQUEST['id'];
			$SQL = "UPDATE suratmasuk SET isaktif = 0 WHERE idsurat = ".$idsurat;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
		else if($type == "suratkeluar")
		{
			$idsurat = $_REQUEST['id'];
			/*$SQL = "DELETE FROM suratkeluar WHERE idsuratkeluar = ".$idsurat;*/
			$SQL = "UPDATE suratkeluar SET isaktif = 0 WHERE idsuratkeluar = ".$idsurat;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
		else if($type == "user")
		{
			$iduser = $_REQUEST['id'];
			
			if($iduser==1)
			{
				echo "Pengguna Ini Tidak Bisa di Hapus";
			}
			else
			{
				$SQL = "DELETE FROM user WHERE iduser = ".$iduser;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
		}
		else if($type == "userlevel")
		{
			$iduserlevel = $_REQUEST['id'];
			
			if($iduserlevel==1)
			{
				echo "Tingkatan Ini Tidak Bisa di Hapus";
			}
			else
			{
				$sql = "SELECT COUNT(a.iduser) AS count FROM user a INNER JOIN userlevel b on a.iduserlevel=b.iduserlevel WHERE b.iduserlevel=".$iduserlevel;
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$count = $row['count'];
				if($count == 0)
				{
					$SQL = "DELETE FROM userlevel WHERE iduserlevel = ".$iduserlevel;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
					//mysql_query($SQL) or die("Could not execute query.".mysql_error());
				}
				else
				{
					echo "Tingkatan Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Pengguna";
				}
			}
		}
	}
?>