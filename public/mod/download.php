<?php
session_start();
require_once('../../config/config.php');
//require_once('../../config/function.php');
//require_once('../../config/aisd.php');
if (($_SESSION['reg']!='yes2019')) {
//	echo '<pre>';
//	print_r($_SERVER);
  //Header("Location: index.php");
	//exit;
}
if (isset($personid) && isset($roleid)) {
	header("Content-Type: application/pdf");
	if ($roleid==2) {
		$sql = "select filename from studentscovid where roleid=2 and personid=$personid";
		$res = $con->query($sql);
		list($filename) = mysqli_fetch_row($res);
		$filename = $coviddir.'student/'.$personid.'/'.$filename;
	} elseif ($roleid==1) {
		$sql = "select filename from studentscovid where roleid=1 and personid=$personid";
		$res = $con->query($sql);
		list($filename) = mysqli_fetch_row($res);
		$filename = $coviddir.'pps/'.$personid.'/'.$filename;
	}
	if (file_exists($filename)) {
		file_force_download($filename,"Vaccination passport($personid)");
	} else {
		echo "no $filename";
	}
}
if (isset($udpersonid) && isset($roleid)) {
	header("Content-Type: application/pdf");
	if ($roleid==2) {
		$sql = "select filename from studentsud where roleid=2 and personid=$udpersonid";
		$res = $con->query($sql);
		list($filename) = mysqli_fetch_row($res);
		$filename = $uddir.'student/'.$udpersonid.'/'.$filename;
	} elseif ($roleid==1) {
		$sql = "select filename from studentsud where roleid=1 and personid=$udpersonid";
		$res = $con->query($sql);
		list($filename) = mysqli_fetch_row($res);
		$filename = $uddir.'pps/'.$udpersonid.'/'.$filename;
	}
	if (file_exists($filename)) {
		file_force_download($filename,"PersonCard($udpersonid)");
	} else {
		echo "no $filename";
	}
}
if (isset($examfiles)) {
	$sql = "select files from biletexamsresfiles where fileid = $examfiles";
	$res = $conapps->query($sql);
	list($filename) = mysqli_fetch_row($res);
	if (file_exists($filename)) {
		file_force_download($filename,"EReport($examfiles)");
	} else {
		echo "no $filename";
	}
}
if (isset($photostudentid)) {
	if (isset($studentid)) {
		if (file_exists($photostudentid)) {
			file_force_download($photostudentid,$studentid);
		} else {
			file_force_download('/var/www/newregister/www/dist/img/student/blank.jpg',$studentid);
		}
	}
	if (isset($tutorid)) {
		if (file_exists($photostudentid)) {
			file_force_download($photostudentid,$tutorid);
		} else {
			file_force_download('/var/www/newregister/www/dist/img/student/blank.jpg',$tutorid);
		}
	}
}
if (isset($phototutorid)) {
	if (isset($studentid)) {
		if (file_exists($photostudentid)) {
			file_force_download($photostudentid,$studentid);
		} else {
			file_force_download('/var/www/newregister/www/dist/img/student/blank.jpg',$studentid);
		}
	}
	if (isset($tutorid)) {
		if (file_exists($phototutorid)) {
			file_force_download($phototutorid,$tutorid);
		} else {
			file_force_download('/var/www/newregister/www/dist/img/student/blank.jpg',$tutorid);
		}
	}
}
if (isset($c_ereport)) {
	$sql = "select file,w,c_jorn from ereport where c_ereport=$c_ereport";
	$res = $conj->query($sql);
	list($file,$w,$c_jorn) = mysqli_fetch_row($res);
	$filename="/sro/ereport/".$_SESSION['semestr']."/$w/$c_jorn/$file";
	if (file_exists($filename)) {
		file_force_download($filename,"EReport($c_ereport)");
	} else {
		echo "no $filename";
	}
}
if (isset($c_srsext)) {
	$sql = "select c_stud,file from jornal_srs_stud where c_srs=$c_srsext";
	$conj -> select_db("journal0$term$year");
	$res = $conj->query($sql) or die($sql.'<br>'.$conj->error);
	list($c_stud,$file) = mysqli_fetch_row($res);
	$srodir = '/sro/0'.$term.$year.'/';
	$filename=$srodir.$c_stud.'/'.$c_jorn.'/'.$file;
	if (file_exists($filename)) {
		file_force_download($filename,"sro($c_stud-$c_srs)");
	} else {
		echo "no $filename";
	}
}
if (isset($c_srs)) {
	$sql = "select c_stud,file from jornal_srs_stud where c_srs=$c_srs";
	$res = $conj->query($sql) or die($sql.'<br>'.$conj->error);
	list($c_stud,$file) = mysqli_fetch_row($res);
	$filename=$srodir.$c_stud.'/'.$c_jorn.'/'.$file;
	if (file_exists($filename)) {
		file_force_download($filename,"sro($c_stud-$c_srs)");
	} else {
		echo "no $filename";
	}
} elseif (isset($c_umkddet)) {
	$sql = "select file from umkddet where c_umkddet=$c_umkddet";
	$res = $conapps->query($sql);
	list($file) = mysqli_fetch_row($res);
	if (file_exists($umkddir.$file)) {
		file_force_download($umkddir.$file,"umkdfile($c_umkddet)");
	} else {
		echo "no $filename";
	}
} elseif (isset($c_a)) {
	$sql = "select file from maila where c_a=$c_a";
	$res = $conapps->query($sql);
	list($file) = mysqli_fetch_row($res);
	if (file_exists($maildir.$file)) {
		file_force_download($maildir.$file,$c_a);
	} else {
		echo "no $filename";
	}
} elseif (isset($c_plagiat)) {
	$sql = "select $file from plagiat where c_plagiat=$c_plagiat";
	$res = $conapps->query($sql);
	list($file) = mysqli_fetch_row($res);
	if (file_exists($plagiatdir.$file)) {
		file_force_download($plagiatdir.$file);
	} else {
		echo "no $filename";
	}

} elseif ($c_umkd) {
	if ($umkd=='umkd') {
		$sql="select files from rup_disc_umkd where c_d_umkd=$c_umkd";
		$res = $conp->query($sql);
		list($file) = mysqli_fetch_row($res);
	} elseif ($umkd=='rk1') {
		$sql="select files from rup_disc_rk1 where c_d_umkd=$c_umkd";
		$res = $conp->query($sql);
		list($file) = mysqli_fetch_row($res);
	} elseif ($umkd=='rk2') {
		$sql="select files from rup_disc_rk2 where c_d_umkd=$c_umkd";
		$res = $conp->query($sql);
		list($file) = mysqli_fetch_row($res);
	} elseif ($umkd=='umm') {
		$sql="select url from rup_disc_umm where c_disc_er=$c_umkd";
		$res = $conp->query($sql);
		list($file) = mysqli_fetch_row($res);
	}
	if (file_exists($umkddir.$file)) {
		file_force_download($umkddir.$file,"file-$umkd($c_umkd)");
	} else {
		echo "no $file";
	}
}  elseif (isset($dirs)) {
	//$d = explode('.', $d);
	if (file_exists($dirs)) {
		file_force_download($dirs,'er');
	} else {
		echo "no $filename";
	}

} elseif (isset($_GET['ecp'])) {
	if (file_exists('/openssl/userkey/'.$ecp.'/a.'.$ecp.'.p12')) {
		file_force_download('/openssl/userkey/'.$ecp.'/a.'.$ecp.'.p12','a.'.$ecp);
	} else {
		echo ' no a.'.$ecp.'.p12';
	}

} elseif (isset($filed)) {
	if (file_exists($filed)) {
		file_force_download($filed,'dwork');
	} else {
		echo "no $filed";
	}
} elseif (isset($aid)) {
	$sql = "select mailfiles from emaila where aid=$aid";
	$res = $conapps->query($sql);
	list($dir) = mysqli_fetch_row($res);
	if (file_exists('/emails/'.$dir)) {
		file_force_download('/emails/'.$dir,$aid.'-attachment');
	} else {
		echo "no $aid\n".'/emails/'.$dir,$aid.'-attachment';
	}
} elseif (isset($aidimage)) {
	header("Content-Type: image/png");
	$sql = "select mailfiles from emaila where aid=$aidimage";
	$res = $conapps->query($sql);
	list($dir) = mysqli_fetch_row($res);
	readfile('/emails/'.$dir);
} elseif (isset($paid)) {
	$sql = "select files from projecta where aid=$paid";
	$res = $conapps->query($sql);
	list($dir) = mysqli_fetch_row($res);
	if (file_exists('/emails/'.$dir)) {
		file_force_download('/emails/'.$dir,$paid.'-attachment');
	} else {
		echo "no $filed";
	}
} elseif (isset($prfid)) {
	$sql = "select filename from practicfiles where fileid=$prfid";
	$res = $conapps->query($sql);
	list($filename) = mysqli_fetch_row($res);
	$file = explode('.',$filename);
	$dir = '/files/practica/'.$studyyear.'/'.$term.'/'.$practicid.'/'.$studentid.'/dnevnik/';
	if (file_exists($dir.$filename)) {
		file_force_download($dir.$filename,$file[0]);
	} else {
		echo "no $dir.$filename";
	}
} elseif (isset($practicidm)) {
	$sql = "select filename from practicmethod where practicid=$practicidm";
	$res = $conapps->query($sql);
	list($filename) = mysqli_fetch_row($res);
	if (file_exists($mpdir.$filename)) {
		file_force_download($mpdir.$filename,$practicidm);
	} else {
		echo "no $mpdir.$filename";
	}
} elseif (isset($zfileid)) {
	$sql = "select dir,filename from plfiles where fileid=$zfileid";
	$res = $conapps->query($sql);
	list($dir,$filename) = mysqli_fetch_row($res);
	if (file_exists($zdndir.$dir.$filename)) {
		file_force_download($zdndir.$dir.$filename,$zfileid);
	} else {
		echo "no $zdndir.$dir.$filename";
	}
} elseif (isset($qualid)) {
	$sql = "select filename from tutorqual where qualID=$qualid";
	$res = $con->query($sql);
	list($filename) = mysqli_fetch_row($res);
	if (file_exists($invdir.'pps/'.$c_pps.'/files/'.$filename)) {
		file_force_download($invdir.'pps/'.$c_pps.'/files/'.$filename,$filename);
	} else {
		echo "no $invdir".'pps/'."$c_pps/files/$filename";
	}
}
