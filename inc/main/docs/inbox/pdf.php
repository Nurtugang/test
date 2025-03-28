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


if (isset($_GET['signlist'])) {
  $signs = explode(',',$signlist);
  for ($i=0;$i<count($signs);$i++) {
    $todata = $signs[$i];
    $pid = getpodrid($todata);
    $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$todata,3,$pid)";
    $condocs->query($sql) or die($sql."<br>".$conapps->error);
  }
}
if (isset($_POST['action'])) {
  if ($action=='proverka') {
    $sigex = explode('=', $url);
    $sigex = $sigex[1];
    $data = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex));
    if (isset($data->signatures[0]->signId)) {
      echo 'ok';
    } else {
      echo 'По заданной ссылке документ не найден';
    }

  } elseif ($action=='save') {
    $sigex = explode('=', $url);
    $sigex = $sigex[1];
    $data = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex));
    if (isset($data->signatures[0]->signId)) {
        // Запрос на подтверждение регистрации
        // с отправкой оригинального файла
        $ch = curl_init("https://sigex.kz/api/".$sigex."/verify");
        move_uploaded_file($_FILES['uploadctls']['tmp_name'],'/tmp/'.$_FILES['uploadctls']['name']);
        $handle = fopen('/tmp/'.$_FILES['uploadctls']['name'], "rb");
        $datafile = fread($handle, filesize('/tmp/'.$_FILES['uploadctls']['name']));
        $datareport = base64_encode($datafile);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/octet-stream'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datafile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $r = json_decode($response);

        echo $r->documentId.'<br>';
        if (isset($r->documentId)) {
          $sign = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex.'/signature/'.$data->signatures[0]->signId.'?signFormat=0'));
      echo '<pre>';
      echo 'https://sigex.kz/api/signature/'.$data->signatures[0]->signId.'?signFormat=0<br>';
      echo $sign->signature.'<br>';
      print_r($sign);
      echo '</pre>';
          $title = $data->title;
          $iin = substr($data->signatures[0]->userId,3,12);
          $subject = explode(',', $data->signatures[0]->subject);
          $role = 5;$ou = '';
          for ($i=0;$i<count($subject);$i++) {
            $k = explode('=', $subject[$i]);
            if ($k[0]=='CN') {
              $fi = $k[1];
            } elseif ($k[0]=='SURNAME') {
              $f = $k[1];
            } elseif ($k[0]=='GIVENNAME') {
              $p = $k[1];
            } elseif ($k[0]=='OU') {
              $ou = $k[1];
              $role = 6;
            }
          }
          $doctypeid = -100;
          $n = str_replace($f.' ', '', $fi);
          echo "$f $n $p<br>";
          $sql = "select personid,roleid from users where iin='$iin' order by roleid";
          $resu = $condocs->query($sql) or die($sql);
          if ($resu->num_rows==0) {
            $sql = "insert into extusers(lastname,firstname,patronymic,iin) values('$f','$n','$p','$iin')";
            $condocs->query($sql) or die($sql);
            $sql = "select last_insert_id()";
            $res = $condocs->query($sql) or die($sql);
            list($personid) = mysqli_fetch_row($res);
            $sql = "insert into users(personid,roleid,lastname,firstname,patronymic,iin,bin) values($pesonid,$roleid,'$f','$n','$p','$iin','$ou')";
            $condocs->query($sql);
          } else {
            list($personid,$roleid) = mysqli_fetch_row($resu);
          }
          echo "$personid $roleid<br>";
          $sql = "insert into documents(author,name,filename,dir,doctypeid,podrid,roleid,sigexid) values($personid,'$title','".$_FILES['uploadctls']['name']."','$dir',-100,0,$roleid,'$sigex')";
          $condocs->query($sql) or die($sql);
          $sql = "select last_insert_id()";
          $res = $condocs->query($sql) or die($sql);
          list($documentid) = mysqli_fetch_row($res);
          echo "Docs - $documentid<br>";
          if (!file_exists($datadir.date('Y'))) {
            mkdir($datadir.date('Y'));
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid)) {
            mkdir($datadir.date('Y').'/'.$doctypeid);
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid)) {
            mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid);
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/')) {
            mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/');
          }
          $dir = date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/';
          echo "$datadir$dir<br>";
          echo $datadir.$dir.$_FILES['uploadctls']['name'].'<br>';
          rename('/tmp/'.$_FILES['uploadctls']['name'], $datadir.$dir.$_FILES['uploadctls']['name']);
          if (file_exists($datadir.$dir.$_FILES['uploadctls']['name'])) {
            $sql = "update documents set dir='$dir' where documentid=$documentid";
            $condocs->query($sql) or die($sql);
            file_put_contents($datadir.$dir.'/Sigex-Base64-'.$personid.'.cms', $sign->signature);
            $sql = "insert into documentfiles(documentid,docname,atdocumentid,author,roleid) values(".$_POST['documentid'].",'',$documentid,$personid,$roleid)";
            $condocs->query($sql) or die($sql);
          } else {
            echo 'No file - '.$_FILES['uploadctls']['tmp_name'].'<br>';
          }
        }
    } else {
      echo 'По заданной ссылке документ не найден';
    }
  }
  exit;
}
//include('function.php');
$Tutor = new Tutor();
$Tutor->con = $con;
$Student = new Student();
$Student->con = $con;
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';
$sql = "select eventid,readdata from events where docid=$documentid and personid=".$_SESSION['personid'];
$res = $condocs->query($sql);
list($eventid,$readdata) = mysqli_fetch_row($res);
if ($readdata==0) {
  $sql = "update events set readdata=1 where eventid=$eventid";
  $condocs->query($sql);
}
$sql = "update events set ";
//$documentidcrypt = mc_encrypt($documentid, $key);
$sql = "select doctypeid from documents where documentid=$documentid";
$res = $condocs->query($sql);
list($doctypeid) = mysqli_fetch_row($res);
$sql = "select groupid from doctypes where doctypeid=$doctypeid";
$res = $condocs->query($sql);
list($groupid) = mysqli_fetch_row($res);
if ($groupid==11) {
    $sql = "select ";
    $sql = "select doccooid,uuid from doccoo where documentid=$documentid";
    $res = $condocs->query($sql);
    list($doccooid,$uuid) = mysqli_fetch_row($res);
    $html = file_get_contents("https://sdo.semgu.kz/documents.php?id=$documentid&zid=$doccooid&code=$uuid");
    //echo "https://sdo.semgu.kz/documents.php?id=$documentid&zid=$doccooid&code=$uuid";
    echo $html;
    exit;
}
?>
<div class="row">

  <script type="text/javascript">
    //$('#pdfframe').html('');
    //$('#pdfframe').load('modules.php?pa=docs-docview2-inbox&documentid=<?=$documentid?>')
  </script>
  <div class="col-lg-5">
    <div class="card card-row card-default">
      <div class="card-header bg-success">
        <h3 class="card-title">
          Состояние
        </h3>
      </div>
      <div class="card-body">
        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Подписание документ № <?=$documentid?></h5>
            <div class="card-tools">
              <p class="mb-0 font-13 text-secondary" style="cursor:pointer;" id="ezsignerexport"><i class="lni lni-pencil"></i> Формат ezSigner</p>
                      <a href="mod/pdf/pdf.php?documentid=<?=$documentid?>&s=<?=session_id()?>" target="_blank" class="mb-0 font-13 text-secondary" id="cardexport">
                        <i class="lni lni-ticket-alt"></i> Карта документа
                      </a>
            </div>
          </div>
          <div class="card-body">
            <?php
            if ($doctypeid!=19) {
              echo signpersons($documentid,$Tutor,$condocs,$Student);
            }
            ?>
          </div>
        </div>


        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Сопроводительные документы</h5>
            <div class="card-tools">
            </div>
          </div>
          <div class="card-body">
            <?php
            if ($doctypeid!=19) {
              echo extdocs($documentid,$Tutor,$condocs);
            }
            ?>
          </div>
        </div>
        <?php
        if ($sign=='0' || $_SESSION['HR']==1) {
        ?>
        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Дополнить лист согласования <?=$sign?></h5>
            <div class="card-tools">
            </div>
          </div>



          <div class="card-body">
                    <div class="form-group">
                        <label>Добавить в лист согласования следующих сотрудников:</label>
                        <select class="select2" multiple="multiple" id="todatadop" data-placeholder="Выберите сотрудников" style="width: 100%;">
                            <?php
                            $lang = $_SESSION['lang'];
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 2";
                            $res = $con->query($sql) or die($con->error);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 3";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors  WHERE not tutors.iinplt IS NULL";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }

                            $sql = "SELECT personid,lastname,firstname,patronymic FROM users  WHERE personid>30000000";
                            $res = $condocs->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <button type="button" class="btn btn-outline-info btn-sm btn-block mt-4" id='addsign'>Добавить в список согласующих документ</button>
                        <script type="text/javascript">
                          $('#todatadop').select2();
                          $('#addsign').click(function(event) {
                            var w = $(window).width()-220;
                            $('#pdfdiv').load('modules.php?pa=docs-pdf-sent&sign=<?=$sign?>&documentid=<?=$documentid?>&signlist='+$('#todatadop').val());
                          });
                        </script>
                    </div>
          </div>

        </div>
          <?php
          }
          ?>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
<div id="scriptdiv" style="display: none;"></div>
<script>
function sendMessageToFlutter(message) {
  if (window.FlutterChannel) {
      window.FlutterChannel.postMessage(message);
  }
}
</script>
<?php


?>
