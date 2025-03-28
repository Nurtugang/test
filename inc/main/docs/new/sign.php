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
require($_SERVER['DOCUMENT_ROOT'].'/api/kalkanFlags&constants.php');
KalkanCrypt_Init();
$tsaurl = "http://tsp.pki.gov.kz:80";
KalkanCrypt_TSASetUrl($tsaurl);
$_SESSION['key'] = 'bWzogfhBJqr6ELxCgP1N0Qbe4';
$key = $_SESSION['key'];
$data = $_POST;
if ($data['base64key']!='') {
    $original_key = $data['base64key'];
    //echo $original_key;
    $password = base64_decode($data['base64password']);
    //echo $data['base64password']."\n";
    //echo $password."\n";
    $p12 = base64_decode($original_key);
} elseif (isset($data['p12file'])) {
    $container = $_SERVER['DOCUMENT_ROOT'].'/'.$data['p12file'];
    //$p12 = base64_decode($_SERVER['DOCUMENT_ROOT'].'/'.$data['p12file']);
    $password = $data['p12password'];
    if (isset($debug)) echo 'srabotalo'."\n";
} else {
    $cipher = "aes-128-cbc";
    $tag = '';
    //$key = "bWzogfhBJqr6ELxCgP1N0Qbe4";
    $outSign = '';
    $sql = "select keytext,keypassword,iv from users where personid=$personid and not keytext is null";
    $res = $condocs->query($sql) or die($sql);
    if ($res->num_rows==0) {
        $data2 = array();
        $data2['error'] = 'No sign key data';
        if (isset($debug)) echo json_encode($data2);
        exit;
    }
    list($keytext,$keypassword,$iv) = mysqli_fetch_row($res);
    $iv = base64_decode($iv);
    $original_key = openssl_decrypt($keytext, $cipher, $key, false, $iv);
    $password = openssl_decrypt($keypassword, $cipher, $key, false, $iv, $tag);
    $p12 = base64_decode($original_key);
    if (isset($debug)) echo $p12;
}
if (!isset($container)) {
    file_put_contents('/tmp/' . $personid . '.p12', $p12);
    $container = '/tmp/' . $personid . '.p12';
}

$alias = "";
$storage = $KCST_PKCS12;
//echo $container."\n";
//echo $password."\n";
$err = KalkanCrypt_LoadKeyStore($storage, $password,$container,$alias);
if ($err > 0){
    if (isset($debug)) echo "Error (row 43): ".$err."\n";
    if (isset($debug)) print_r("Error (row 44): ".KalkanCrypt_GetLastErrorString()."\n\n\n");
    die('password: '.$password."\n".base64_encode('1'));
}
$flags_sign = 722;
$inData = $base64file;
//echo $inData;
$err = KalkanCrypt_SignData($alias, $flags_sign, $inData, $outSign);
if ($err > 0){
    if (isset($debug)) echo "Error (row 54): ".$err."\n";
    if (isset($debug)) print_r(KalkanCrypt_GetLastErrorString());
} else {
    //file_put_contents('sign.data', $outSign);
    $outSign = str_replace('-----BEGIN CMS-----','',$outSign);
    $outSign = str_replace('-----END CMS-----','',$outSign);
    $outSign = str_replace("\n",'',$outSign);
    $CMS = $outSign;
}
//$dataout['cms'] = $CMS;
//echo $CMS;
//exit;


if (!file_exists($datadir.date('Y'))) {
    mkdir($datadir.date('Y'));
}
if (!file_exists($datadir.date('Y').'/'.$doctypeid)) {
    mkdir($datadir.date('Y').'/'.$doctypeid);
}
if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid)) {
    mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid);
}
$dir = date('Y').'/'.$doctypeid.'/'.$personid.'/';

$ext = pathinfo($filename, PATHINFO_EXTENSION);
$pid = getpodrid($personid);

$sql = "insert into documents(author,name,filename,dir,doctypeid,podrid,roleid) values($personid,'$docname','$filename','$dir',$doctypeid,$pid,4)";
$condocs->query($sql) or die($sql."<br>".$condocs->error);
$sql = "select last_insert_id()";
$res = $condocs->query($sql) or die($sql."<br>".$condocs->error);
list($documentid) = mysqli_fetch_row($res);
$dir = $dir.$documentid;
if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid)) {
    mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid);
}
//$dir = date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid.'/';
if ($sign1 != '') {
    if ($sign1 != '') {
        $pid = getpodrid($sign1);
        $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$sign1,1,$pid)";
        $condocs->query($sql) or die($sql . "<br>" . $condocs->error);
    }
}
if ($sign2 != '') {
    $a = explode(',', $sign2);
    for ($i = 0; $i < count($a); $i++) {
        $pid = getpodrid($a[$i]);
        $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$a[$i],2,$pid)";
        $condocs->query($sql) or die($sql . "<br>" . $condocs->error);
    }
}
if ($sign3 != '') {
    $a = explode(',', $sign3);
    for ($i = 0; $i < count($a); $i++) {
        $pid = getpodrid($a[$i]);
        $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$a[$i],3,$pid)";
        $condocs->query($sql) or die($sql . "<br>" . $condocs->error);
    }
}
if ($isp != '') {
    $a = explode(',', $isp);
    for ($i = 0; $i < count($a); $i++) {
        $pid = getpodrid($a[$i]);
        $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$a[$i],4,$pid)";
        $condocs->query($sql) or die($sql . "<br>" . $condocs->error);
    }
}
$pid = getpodrid($personid);
$sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$personid,4,$pid)";
$condocs->query($sql) or die($sql."<br>".$condocs->error);

$filename = $datadir.$dir.'/'.$filename;
//echo "\n".$filename."\n";
file_put_contents($filename,base64_decode($base64file));
file_put_contents($datadir.$dir.'/Base64-'.$personid.'.cms', $CMS);

// Подготовка запроса к ИС Sigex (https://sigex.kz, info@sigex.kz)
// Запрос на регистрацию документа ИС сиджекс
$docnamesigex = $docname;
$json = '{
		  "title": "'.$docnamesigex.'",
		  "description": "Документ SemguAIS",
		  "signType": "cms",
		  "signature": "'.$CMS.'"
		}';
$ch = curl_init("https://sigex.kz/api");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
// Выполнение запроса
$response = curl_exec($ch);
$r = json_decode($response,true);
if ($r['message']!='') {
    $dataout['error'] = 'error row 152 ' . $r['message'];
    $r = json_encode($dataout);
    if (isset($debug)) echo $r;
    exit;
}
// Сохранение оригинальной ЭЦП в базе данных
$sql = "update documents set signature='".$CMS."',sigexid = '".$r['documentId']."',dir='$dir/' where documentid=$documentid";
$sigexid = $r['documentId'];
if ($condocs->query($sql)) {

} else {
    $dataout['error'] = $condocs->error;
    $r = json_encode($dataout);
    if (isset($debug)) echo $r;
    //echo $condocs->error.'<br>';
    // $sql.'<br>';
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
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec($ch);
$r = json_decode($response,true);
if (isset($r['documentId'])) {

} else {
    $dataout['error'] = 'error ' . $r['message'];
    $r = json_encode($dataout);
    if (isset($debug)) echo $r;
    exit;
}


$response = file_get_contents('https://sigex.kz/api/'.$r['documentId']);

$r2 = json_decode($response,true);

$signid = $r2['signatures'][0]['signId'];

$response = file_get_contents('https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0');
//echo 'https://sigex.kz/api/'.$r['documentId'].'/signature/'.$signid.'?signFormat=0';
$r2 = json_decode($response,true);
if ($r2['documentId']==$r['documentId'] && $r2['signId']==$signid) {
    file_put_contents($datadir.$dir.'/Sigex-Base64-'.$personid.'.cms', $r2['signature']);
    $sql = "update documents set status=1,authorsignid=$signid,signature='".$r2['signature']."' where documentid=$documentid";
    $condocs->query($sql) or die($condocs->error);
}

$dataout['documentid'] = $documentid;
$dataout['sigexid'] = $sigexid;
//$dataout['error'] = '';
$r = json_encode($dataout);
//echo $r;
?>
<div class="col">
							<div class="card mb-5 mb-lg-0">
								<div class="card-header bg-primary py-3">
									<h5 class="card-title text-white text-uppercase text-center">Документ подписан</h5>
									<h6 class="card-price text-white text-center"><?=$documentid?><span class="term">/<?=$sigexid?></span></h6>
								</div>
								<div class="card-body">
									<div class="d-grid"> <a href="#" class="btn btn-danger my-2 radius-30">Закрыть</a></div>
								</div>
							</div>
						</div>
