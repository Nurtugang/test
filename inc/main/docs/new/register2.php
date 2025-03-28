<?php
function getpodrid($personid) {
    global $con;
    $sql = "select id from structural_subdivision where dean = $personid";
    $res = $con->query($sql);// or die($sql);
    if ($res->num_rows==0) {
        return 0;
    } else {
        list($id) = mysqli_fetch_row($res);
        return $id;
    }
}
if (isset($_POST['cms'])) {
	require('api/kalkanFlags&constants.php');
	KalkanCrypt_Init();
	$tsaurl = "http://tsp.pki.gov.kz:80";
	KalkanCrypt_TSASetUrl($tsaurl);
	$error = '';
	$outCert = '';
	$outSign = $_POST['cms'];
	$flags_sign = $KC_SIGN_CMS + $KC_IN_BASE64 + $KC_DETACHED_DATA + $KC_WITH_CERT + $KC_OUT_PEM;
	$inSignID = 1;
	$err = KalkanCrypt_getCertFromCMS($outSign, $inSignID, $flags_sign, $outCert);
	$inCert = $outCert;
	//echo $outCert;
	$err = KalkanCrypt_X509CertificateGetInfo($KC_CERTPROP_SUBJECT_SERIALNUMBER,$outCert, $OutData);
	if ($err > 0) {
		if ($err != 149946424){echo "Error: ".$err."\n";}
	} else {
		$iin = explode('=', $OutData);
		$iin = substr($iin[1],3,12);
		if ($_SESSION['iin'] != $iin) {
			echo "IIN - ".$iin.'<br>ИИН ЭЦП не соответствует ИИН авторизации';
			exit;
		}
	}
	$err = KalkanCrypt_X509CertificateGetInfo($KC_CERTPROP_KEY_USAGE,$outCert, $OutData);
	if ($err > 0) {
		if ($err != 149946424){echo "Error: ".$err."\n";}
	} else {
		$keyusage = strpos($OutData,'digitalSignature nonRepudiation');
		if ($keyusage===false) {
			echo "KeyUsage - ".$OutData;
			exit;
		}
	}
	$roleid = $_SESSION['roleid'];
	if ($roleid==2 || $roleid==8 || $roleid==6 || $roleid==9) {
		$err = KalkanCrypt_X509CertificateGetInfo($KC_CERTPROP_EXT_KEY_USAGE,$outCert, $OutData);
		if ($err > 0) {
			if ($err != 149946424){echo "Error: ".$err."\n";}
		} else {
			$keyusage = strpos($OutData,'1.2.398.3.3.4.1.2');
			if ($keyusage === false) {
				echo "ExtKeyUsage - ".$OutData.' Требуется ключ юридического лица';
				exit;
			}
		}
	}

	if (!isset($doctypeid)) {
		$doctypeid = 0;
	}

	if (isset($_FILES['uploadctl'])) {
	  if (!file_exists($datadir.date('Y'))) {
	    mkdir($datadir.date('Y'));
	  }
	  if (!file_exists($datadir.date('Y').'/'.$doctypeid)) {
	    mkdir($datadir.date('Y').'/'.$doctypeid);
	  }
	  if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'])) {
	    mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid']);
	  }
	  $dir = date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/';
	  $ext = pathinfo($_FILES['uploadctl']['name'], PATHINFO_EXTENSION);
	  $pid = getpodrid($_SESSION['personid']);
	  $sql = "insert into documents(author,name,filename,dir,doctypeid,podrid,roleid) values(".$_SESSION['personid'].",'$docname','".$_FILES['uploadctl']['name']."','$dir',$doctypeid,$pid,".$_SESSION['roleid'].")";
	  $condocs->query($sql) or die($sql."<br>".$conapps->error);
	  $sql = "select last_insert_id()";
	  $res = $condocs->query($sql) or die($sql."<br>".$conapps->error);
	  list($documentid) = mysqli_fetch_row($res);
	  if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid)) {
	    mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid);
	  }
	  $telegramid = $_SESSION['telegramid'];
	  $dir = date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid.'/';
	  if (isset($todata)) {
		  if ($todata>0) {
			  $pid = getpodrid($todata);
			  $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$todata,1,$pid)";
			  $condocs->query($sql) or die($sql."<br>".$conapps->error);
			  $text = 'Вам адресован документ в системе электронного документооборота № '.$documentid;
          	  $sql = "insert into events(personid,data,ais,docid) values($todata,'$text1','SDO',$documentid,NULL)";
          	  $condocs->query($sql);
		  }
	  }
	  $text = 'Вам адресован на согласование документ в системе электронного документооборота № '.$documentid;
	  if (isset($todatals1)) {
		  if ($todatals1>0) {
		  	$pid = getpodrid($todatals1);
			  $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$todatals1,2,$pid)";
			  $condocs->query($sql) or die($sql."<br>".$conapps->error);
          	  $sql = "insert into events(personid,data,ais,docid) values($todatals1,'$text','SDO',$documentid,NULL)";
          	  $condocs->query($sql);
		  }
	  }
	  if (isset($todatals2)) {
		  if ($todatals2!='') {
		  	  switch ($doctypeid) {
		  	  	case '2':
		  	  		//$todatals2 .= ',2031';
		  	  		break;
		  	  	
		  	  	default:
		  	  		# code...
		  	  		break;
		  	  }
			  $a = explode(',', $todatals2);
			  for ($i=0;$i<count($a);$i++) {
			  	  $pid = getpodrid($a[$i]);
				  $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$a[$i],3,$pid)";
				  $condocs->query($sql);// or die($sql."<br>".$conapps->error);
	          	  $sql = "insert into events(personid,data,ais,docid) values($a[$i],'$text','SDO',$documentid,NULL)";
    	      	  $condocs->query($sql);
			  }
		  }
	  }
	  if ($todatals3!='') {
		  $a = explode(',', $todatals3);
		  for ($i=0;$i<count($a);$i++) {
		  	  $pid = getpodrid($a[$i]);
			  $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$a[$i],4,$pid)";
			  $condocs->query($sql) or die($sql."<br>".$conapps->error);
			  $text = 'Вам адресован на исполнение документ в системе электронного документооборота № '.$documentid;
          	  $sql = "insert into events(personid,data,ais,docid) values($a[$i],'$text','SDO',$documentid,NULL)";
   	      	  $condocs->query($sql);
		  }
	  }


  $tmp = $_FILES['uploadctl']['tmp_name'];
  //mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid']);



    if (move_uploaded_file($_FILES['uploadctl']['tmp_name'], $datadir.$dir.'/'.$_FILES['uploadctl']['name'])) {
    	$sql = "update documents set dir='$dir' where documentid=$documentid";
    	$condocs->query($sql);
     } else {
      $sql = "delete from documentsignlists where documentid=$documentid";
      $condocs->query($sql);
      $sql = "delete from documents where documentid=$documentid";
      $condocs->query($sql);
     }
     $filename = $datadir.$dir.'/'.$_FILES['uploadctl']['name'];

		// Запись сформированной ЭЦП на сервере
		file_put_contents($datadir.$dir.'/Base64-'.$_SESSION['personid'].'.cms', $_POST['cms']);

		// Подготовка запроса к ИС Sigex (https://sigex.kz, info@sigex.kz)
		// Запрос на регистрацию документа ИС сиджекс
		$docnamesigex = substr($docname,0,10);
		$json = '{
		  "title": "'.$docnamesigex.'",
		  "description": "Документ SemguAIS",
		  "signType": "cms",
		  "signature": "'.$_POST['cms'].'"
		}';
		$ch = curl_init("https://sigex.kz/api");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Выполнение запроса
		$response = curl_exec($ch);
		$r = json_decode($response,true);
		if ($r['message']!='') {
			echo 'error ' . $r['message'];
			exit;
		}
		// Сохранение оригинальной ЭЦП в базе данных
		$sql = "update documents set signature='".$_POST['cms']."',sigexid = '".$r['documentId']."' where documentid=$documentid";
		if ($condocs->query($sql)) {

		} else {
			echo $condocs->error.'<br>';
			echo $sql.'<br>';
			exit;
		}
		// Запрос на подтверждение регистрации
		// с отправкой оригинального файла
		$ch = curl_init("https://sigex.kz/api/".$r['documentId']."/data");
		$handle = fopen($filename, "rb");
		$data = fread($handle, filesize($filename));
		$datareport = base64_encode($data);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/octet-stream'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$r = json_decode($response,true);
		if (isset($r['documentId'])) {

		} else {
			echo 'error '.$r["message"];
			exit;
		}


		$response = file_get_contents('https://sigex.kz/api/'.$r['documentId']);

		$r2 = json_decode($response,true);

		$signid = $r2['signatures'][0]['signId'];

		$response = file_get_contents('https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0');
		//echo 'https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0';
		$r2 = json_decode($response,true);
		if ($r2['documentId']==$r['documentId'] && $r2['signId']==$signid) {
			file_put_contents($datadir.$dir.'/Sigex-Base64-'.$_SESSION['personid'].'.cms', $r2['signature']);
			$sql = "update documents set status=1,authorsignid=$signid,signature='".$r2['signature']."' where documentid=$documentid";
			$condocs->query($sql) or die($condocs->error);
		}

	}
}
echo '<h3><b>Документ подписан, перейдите в папку "Исходящие"</b></h3>';