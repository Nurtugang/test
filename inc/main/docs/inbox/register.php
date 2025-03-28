<?php
if (isset($_POST['cms'])) {


	require('api/kalkanFlags&constants.php');
	KalkanCrypt_Init();
	$tsaurl = "http://tsp.pki.gov.kz:80";
	KalkanCrypt_TSASetUrl($tsaurl);


	if (isset($_POST['remote'])) {
		$cipher = "aes-128-cbc";
		$tag = '';
		$key = "bWzogfhBJqr6ELxCgP1N0Qbe4";
		$outSign = '';
		$sql = "select keytext,keypassword,iv from users where personid=".$_SESSION['personid']." and not keytext is null";
        //die($sql);
		$res = $condocs->query($sql) or die($sql);
		list($keytext,$keypassword,$iv) = mysqli_fetch_row($res);
		$iv = base64_decode($iv);
		$original_key = openssl_decrypt($keytext, $cipher, $key, $options=0, $iv);
		$password = openssl_decrypt($keypassword, $cipher, $key, $options=0, $iv, $tag);
		//die($password);
		$p12 = base64_decode($original_key);
		file_put_contents('/tmp/'.$_SESSION['personid'].'.p12',$p12);

		$container = '/tmp/'.$_SESSION['personid'].'.p12';
		$alias = "";
		$storage = $KCST_PKCS12;
		$err = KalkanCrypt_LoadKeyStore($storage, $password,$container,$alias);
		if ($err > 0){
			echo "Error: 32".$err."\n";
			print_r(KalkanCrypt_GetLastErrorString());
			die($password);
		}
		$flags_sign = 722;
		$inData = $_POST['remote'];
		$err = KalkanCrypt_SignData($alias, $flags_sign, $inData, $outSign);
		if ($err > 0){
			echo "Error: 49".$err."\n";
			print_r(KalkanCrypt_GetLastErrorString());
		} else {
			//file_put_contents('sign.data', $outSign);
				$outSign = str_replace('-----BEGIN CMS-----','',$outSign);
				$outSign = str_replace('-----END CMS-----','',$outSign);
				$outSign = str_replace("\n",'',$outSign);
			$_POST['cms'] = $outSign;
			//echo $outSign.'<br>';
			if ($_SESSION['personid']==566) {
				//die($password);
			}
		}
		//exit;
	}
	//echo $_POST['cms'];
	//exit;
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
		if ($err != 149946424){echo "Error:67 ".$err."\n";}
	} else {
		$iin = explode('=', $OutData);
		$iin = substr($iin[1],3,12);
		if ($_SESSION['iin'] != $iin) {
			echo "IIN - ".$iin.'<br>ИИН ЭЦП не соответствует ИИН авторизации';
			exit;
		}
	}



	if ($_POST['sigexid']=='') {
		include('obh.php');
		exit;
	}
		// Подготовка запроса к ИС Sigex (https://sigex.kz, info@sigex.kz)
		// Запрос на регистрацию документа ИС сиджекс
		$json = '{
		  "signType": "cms",
		  "signature": "'.$_POST['cms'].'"
		}';
		//echo $json;
		$ch = curl_init("https://sigex.kz/api/".$_POST['sigexid']);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Выполнение запроса
		$response = curl_exec($ch);
		$r = json_decode($response,true);
		if ($r['message']!='') {
			echo 'error 99 ' . $r['message'];
			exit;
		} else {
			echo '<pre>';
			$response = file_get_contents('https://sigex.kz/api/'.$r['documentId']);
			$r2 = json_decode($response,true);
			$signid = $r2['signatures'][0]['signId'];
			for ($i=0;$i<count($r2['signatures']);$i++) {
				if ('IIN'.$_SESSION['iin']==$r2['signatures'][$i]['userId']) {
					$signid = $r2['signatures'][$i]['signId'];
				}
			}
			$response = file_get_contents('https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0');
			//echo 'https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0';
			$r2 = json_decode($response,true);
			if ($r2['documentId']==$r['documentId'] && $r2['signId']==$signid) {
				file_put_contents($datadir.$dir.$documentid.'/Sigex-Base64-'.$signid.'-'.$_SESSION['personid'].'.cms', $r2['signature']);
				$sql = "update documents set status=0,authorsignid=$signid where documentid=$documentid";
				$sql = "update documentsignlists set sigexsignid = $signid,signdate=now(),signature='".$r2['signature']."',status=$status,rezolution='$rezolution',signpersonid=".$_SESSION['personid']." where documentid=$documentid and personid=".$_SESSION['personid'];
				$condocs->query($sql) or die($condocs->error."\n".$sql);
				$sql = "select author from documents where documentid=".$documentid;
				$res = $condocs->query($sql);
				list($author) = mysqli_fetch_row($res);
		          $sql = "select telegramid from users where personid=$author";
		          $res = $condocs->query($sql);
		          list($telegramid) = mysqli_fetch_row($res);
		          if ($telegramid>300) {
		          	$text = 'Ваш документ в системе электронного документооборота № '.$documentid.".\n".'Подписан '.$_SESSION['fio'];
		          	include ($_SERVER['DOCUMENT_ROOT'].'/telegram.php');
		          }
				if ($_POST['todatals']!='') {
					$list = explode(',', $_POST['todatals']);
					for ($i=0;$i<count($list);$i++) {
						$sql = "insert into documentsignlists(documentid,personid,type,signdate,status) values($documentid,$list[$i],4,(select now()),1)";
						$condocs->query($sql);
				          $sql = "select telegramid from users where personid=$list[$i]";
				          $res = $condocs->query($sql);
				          list($telegramid) = mysqli_fetch_row($res);
				          if ($telegramid>300) {
				          	$text = 'Вам направлен документ для исполнения в системе электронного документооборота № '.$documentid;
				          	include ($_SERVER['DOCUMENT_ROOT'].'/telegram.php');
				          }
					}
				}
			}
		}
} else {
	$sql = "update documentsignlists set signdate=now(),status=$status,rezolution='$rezolution' where documentid=$documentid and personid=".$_SESSION['personid'];
	$condocs->query($sql) or die($sql);
}

?>
